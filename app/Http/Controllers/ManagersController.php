<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManagersController extends Controller
{
    public function index()
    {
        $totalTrips = Trip::count();
        $availableTrips = Trip::where('status', 'Available')->count();
        $ongoingTrips = Trip::where('status', 'In_Progress')->count();
        $completedTrips = Trip::where('status', 'Completed')->count();
        $cancelledTrips = Trip::where('status', 'Cancelled')->count();

        // جلب أحدث الرحلات (مثلاً آخر 5 رحلات)
        $latestTrips = Trip::leftJoin('users','users.id','=','trips.driver_id')
        ->leftJoin('cities','cities.id','=','trips.city_id')
        ->select('trips.*','users.name','cities.city_name')
        ->latest()->take(10)
        ->get();

        return view('manager.home', compact(
            'latestTrips',
            'totalTrips',
            'availableTrips',
            'ongoingTrips',
            'completedTrips',
            'cancelledTrips'
        ));
    }
    public function getUsers()
    {
        // جلب جميع المستخدمين
        $users = User::leftJoin('cities','cities.id','users.city_id')
        ->select('users.*', 'cities.city_name')
        ->get();

        return view('manager.users', compact('users'));
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
                        1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل',
                        5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس',
                        9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر'
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
                1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل',
                5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس',
                9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر'
            ];
            $monthLabel = $arMonths[$date->month] . ' ' . $date->year;
        }

        return view('manager.completed-trips', compact(
            'validMonth',
            'months',
            'trips',
            'month',
            'q',
            'monthLabel'
        ));
    }
}
