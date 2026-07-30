<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    public function index()
    {
        // $trips = Trip::where('city_id', '=', session('city_id'))
        //     ->where('status', 'Available')
        //     ->get();

        $driverId = session('user_id');

        // جلب الرحلة النشطة الحالية
        $activeTrip = Trip::where('driver_id', $driverId)
            ->where('status', 'In_Progress')
            ->first();

        // جلب الرحلات المتاحة إذا لم تكن هناك رحلة نشطة
        $availableTrips = collect();
        if (!$activeTrip) {
            $availableTrips = Trip::where('status', 'Available')
                ->where('city_id', session('city_id'))
                ->orderBy('created_at', 'asc')
                ->get();
        }
        return view('driver.home', compact('availableTrips', 'activeTrip'));
    }

    public function startTrip(Request $request)
    {
        $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'odometer_image' => 'required|image|max:5120', // بحد أقصى 5 ميجابايت
        ]);

        $driverId = session('user_id');
        $tripId = $request->trip_id;

        $trip = Trip::where('id', $tripId)
            ->where('status', 'Available')
            ->first();

        if (!$trip) {
            return back()->with('error', 'الرحلة غير متاحة أو غير مخصصة لك.');
        }

        if ($request->hasFile('odometer_image')) {
            $file = $request->file('odometer_image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            Storage::disk('public')->putFileAs(
                'odometer_images',
                $request->file('odometer_image'),
                $fileName
            );

            $trip->update([
                'driver_id' => $driverId,
                'status' => 'In_Progress',
                'stage_1_time' => now(),
                'odometer_image' => $fileName,
            ]);

            return back()->with('success', 'تم بدء الرحلة بنجاح.');
        }

        return back()->with('error', 'فشل رفع صورة العداد.');
    }

    public function updateStage(Request $request)
    {
        $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'stage' => 'required|in:2,3,4',
        ]);

        $driverId = session('user_id');
        $tripId = $request->trip_id;
        $stage = $request->stage;

        $trip = Trip::where('id', $tripId)
            ->first();

        if (!$trip) {
            return back()->with('error', 'الرحلة غير موجودة.');
        }

        $updateData = [];
        if ($stage == 2) {
            $updateData['stage_2_time'] = now();
        } elseif ($stage == 3) {
            $updateData['stage_3_time'] = now();
        } elseif ($stage == 4) {
            $updateData['stage_4_time'] = now();
            $updateData['status'] = 'Completed';
        }

        if (!empty($updateData)) {
            $trip->update($updateData);
            return back()->with('success', 'تم تحديث المرحلة بنجاح.');
        }

        return back()->with('error', 'فشل التحديث.');
    }


    public function completedTrips(Request $request)
    {
        $driverId = session('user_id');
        $month = $request->input('month');
        $q = trim($request->input('q', ''));

        // التحقق من صحة صيغة الشهر (YYYY-MM)
        $validMonth = $month && preg_match('/^\d{4}-\d{2}$/', $month);

        $months = [];
        $trips = collect();
        $totalPages = 1;
        $page = $request->input('page', 1);
        $monthLabel = '';

        if (!$validMonth) {
            // جلب الأشهر التي تحتوي على رحلات مكتملة لهذا السائق فقط مع عدد الرحلات لكل شهر
            $months = Trip::where('driver_id', $driverId)
                ->where('status', 'Completed')
                ->selectRaw("DATE_FORMAT(trip_date, '%Y-%m') as ym, COUNT(*) as count")
                ->groupBy('ym')
                ->orderBy('ym', 'desc')
                ->get()
                ->map(function ($item) {
                    $date = Carbon::createFromFormat('Y-m', $item->ym);
                    // تسمية الشهر بالعربية
                    $arMonths = [
                        1 => 'يناير',
                        2 => 'فبراير',
                        3 => 'مارس',
                        4 => 'أبريل',
                        5 => 'مايو',
                        6 => 'ييونيو',
                        7 => 'يوليو',
                        8 => 'أغسطس',
                        9 => 'سبتمبر',
                        10 => 'أكتوبر',
                        11 => 'نوفمبر',
                        12 => 'ديسمبر'
                    ];
                    $label = $arMonths[$date->month] . ' ' . $date->year;

                    return [
                        'ym' => $item->ym,
                        'label' => $label,
                        'count' => $item->count
                    ];
                });
        } else {
            $query = Trip::where('driver_id', $driverId)
                ->where('status', 'Completed')
                ->whereRaw("DATE_FORMAT(trip_date, '%Y-%m') = ?", [$month]);

            if ($q !== '') {
                $query->where(function ($subQuery) use ($q) {
                    $subQuery->where('flight_number', 'like', "%{$q}%")
                        ->orWhere('destination', 'like', "%{$q}%");
                });
            }

            $trips = $query->orderBy('stage_4_time', 'desc')->paginate(12)->appends([
                'month' => $month,
                'q' => $q
            ]);

            $date = Carbon::createFromFormat('Y-m', $month);
            $arMonths = [
                1 => 'يناير',
                2 => 'فبراير',
                3 => 'مارس',
                4 => 'أبريل',
                5 => 'مايو',
                6 => 'ييونيو',
                7 => 'يوليو',
                8 => 'أغسطس',
                9 => 'سبتمبر',
                10 => 'أكتوبر',
                11 => 'نوفمبر',
                12 => 'ديسمبر'
            ];
            $monthLabel = $arMonths[$date->month] . ' ' . $date->year;
        }

        return view('driver.completed-trips', compact(
            'validMonth',
            'months',
            'trips',
            'month',
            'q',
            'monthLabel'
        ));
    }
}
