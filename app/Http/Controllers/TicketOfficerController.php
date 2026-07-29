<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketOfficerController extends Controller
{
    public function index()
    {
        $success = session('success');
        $error = session('error');

        // جلب الرحلات من قاعدة البيانات
        $trips = Trip::join('cities', 'trips.city_id', '=', 'cities.id')
            ->leftJoin('users', 'users.id', 'trips.driver_id')
            ->select('trips.*', 'cities.city_name', 'users.name as driver_name')
            ->where('trips.status', '!=', 'Completed')
            ->where('trips.status', '!=', 'Cancelled')
            ->orderByDesc('created_at')
            ->get();

        return view('ticket_officer.home', compact('trips', 'success', 'error'));
    }

    public function createTrip()
    {
        $cities = City::all();

        return view('ticket_officer.create-trip', compact('cities'));
    }
    public function storeTrip(Request $request)
    {
        // التحقق من صحة البيانات المدخلة
        $request->validate([
            'trip_date' => 'required|date',
            'destination' => 'required|string|max:255',
            'flight_number' => 'required|string|max:255',
            'workers_count' => 'required|integer|min:0',
        ]);

        // إنشاء رحلة جديدة
        Trip::create([
            'trip_date' => $request->trip_date,
            'city_id' => $request->city_id,
            'destination' => $request->destination,
            'flight_number' => $request->flight_number,
            'workers_count' => $request->workers_count,
            'status' => 'Available',
            'notes' => $request->notes,
        ]);

        return redirect()->route('ticket-officer.home')->with('success', 'تم إضافة الرحلة بنجاح');
    }

    public function editTrip($id)
    {
        $trip = Trip::findOrFail($id);
        $cities = City::all();

        return view('ticket_officer.edit-trip', compact('trip', 'cities'));
    }
    public function updateTrip(Request $request, $id)
    {
        // التحقق من صحة البيانات المدخلة
        $request->validate([
            'trip_date' => 'required|date',
            'destination' => 'required|string|max:255',
            'flight_number' => 'required|string|max:255',
            'workers_count' => 'required|integer|min:0',
        ]);

        // تحديث بيانات الرحلة
        $trip = Trip::findOrFail($id);
        $trip->update([
            'trip_date' => $request->trip_date,
            'city_id' => $request->city_id,
            'destination' => $request->destination,
            'flight_number' => $request->flight_number,
            'workers_count' => $request->workers_count,
            'notes' => $request->notes,
        ]);

        return redirect()->route('ticket-officer.home')->with('success', 'تم تحديث بيانات الرحلة بنجاح');
    }

    public function cancelledTrip($id)
    {
        $trip = Trip::findOrFail($id);
        $trip->update(
            [
                'status' => 'Cancelled'
            ]
        );

        return redirect()->route('ticket-officer.home')->with('success', 'تم الغاء الرحلة بنجاح');
    }


    public function completedTrips(Request $request)
    {
        $month = $request->input('month');
        $q = trim($request->input('q', ''));

        // التحقق من صحة صيغة الشهر (YYYY-MM)
        $validMonth = $month && preg_match('/^\d{4}-\d{2}$/', $month);

        $months = [];
        $trips = collect();
        $monthLabel = '';

        if (!$validMonth) {
            // جلب الأرشيف الشهري لجميع الرحلات المكتملة لجميع السائقين
            $months = Trip::where('status', 'Completed')
                ->orWhere('status', 'Cancelled')
                ->selectRaw("DATE_FORMAT(trip_date, '%Y-%m') as ym, COUNT(*) as count")
                ->groupBy('ym')
                ->orderBy('ym', 'desc')
                ->get()
                ->map(function ($item) {
                    $date = Carbon::createFromFormat('Y-m', $item->ym);
                    $arMonths = [
                        1 => 'يناير',
                        2 => 'فبراير',
                        3 => 'مارس',
                        4 => 'أبريل',
                        5 => 'مايو',
                        6 => 'يونيو',
                        7 => 'يوليو',
                        8 => 'أغسطس',
                        9 => 'سبتمبر',
                        10 => 'أكتوبر',
                        11 => 'نوفمبر',
                        12 => 'ديسمبر'
                    ];

                    return [
                        'ym' => $item->ym,
                        'label' => $arMonths[$date->month] . ' ' . $date->year,
                        'count' => $item->count,
                    ];
                });
        } else {
            // جلب الرحلات مع علاقة السائق (driver)
            $query = Trip::leftJoin('users', 'users.id', 'trips.driver_id')
                ->select('trips.*', 'users.name as driver_name')
                ->where('status', 'Completed')
                ->whereRaw("DATE_FORMAT(trip_date, '%Y-%m') = ?", [$month])
                ->orWhere('status', 'Cancelled')
                ->whereRaw("DATE_FORMAT(trip_date, '%Y-%m') = ?", [$month]);

            if ($q !== '') {
                $query->where(function ($subQuery) use ($q) {
                    $subQuery->where('flight_number', 'like', "%{$q}%")
                        ->orWhere('destination', 'like', "%{$q}%")
                        ->orWhere('driver_name', 'like', "%{$q}%")
                        ->orWhereHas('driver', function ($userQuery) use ($q) {
                            $userQuery->where('name', 'like', "%{$q}%");
                        });
                });
            }

            $trips = $query->orderBy('stage_4_time', 'desc')
                ->orderBy('trip_date', 'desc')
                ->paginate(12)
                ->appends(['month' => $month, 'q' => $q]);

            $date = Carbon::createFromFormat('Y-m', $month);
            $arMonths = [
                1 => 'يناير',
                2 => 'فبراير',
                3 => 'مارس',
                4 => 'أبريل',
                5 => 'مايو',
                6 => 'يونيو',
                7 => 'يوليو',
                8 => 'أغسطس',
                9 => 'سبتمبر',
                10 => 'أكتوبر',
                11 => 'نوفمبر',
                12 => 'ديسمبر'
            ];
            $monthLabel = $arMonths[$date->month] . ' ' . $date->year;
        }

        return view('ticket_officer.completed-trips', compact(
            'validMonth',
            'months',
            'trips',
            'month',
            'q',
            'monthLabel'
        ));
    }
}
