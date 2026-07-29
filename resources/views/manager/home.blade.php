@extends('layouts.manager_app')

@section('content')
    <div class="main-content">
        <div class="top-header">
            <h4 class="mb-0 text-secondary">نظرة عامة</h4>
            <div class="user-info">
                <i class="bi bi-person-circle fs-5"></i>
                <span class="fw-bold mx-2">{{ session('name') }}</span>
            </div>
        </div>

        <!-- قسم الإحصائيات -->
        <div class="row mb-4">
    <div class="col-12 col-md mb-3">
        <div class="card stat-card bg-info text-white h-100">
            <div class="card-body">
                <h6>إجمالي الرحلات</h6>
                <h2>{{ $totalTrips ?? 0 }}</h2>
                <i class="bi bi-map-fill stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-12 col-md mb-3">
        <div class="card stat-card bg-warning text-dark h-100">
            <div class="card-body">
                <h6>متاحة</h6>
                <h2>{{ $availableTrips ?? 0 }}</h2>
                <i class="bi bi-clock-history stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-12 col-md mb-3">
        <div class="card stat-card bg-primary text-white h-100">
            <div class="card-body">
                <h6>جارية</h6>
                <h2>{{ $ongoingTrips ?? 0 }}</h2>
                <i class="bi bi-truck stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-12 col-md mb-3">
        <div class="card stat-card bg-success text-white h-100">
            <div class="card-body">
                <h6>مكتملة</h6>
                <h2>{{ $completedTrips ?? 0 }}</h2>
                <i class="bi bi-check-circle-fill stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-12 col-md mb-3">
        <div class="card stat-card bg-danger text-white h-100">
            <div class="card-body">
                <h6>ملغية</h6>
                <h2>{{ $cancelledTrips ?? 0 }}</h2>
                <i class="bi bi-x-circle-fill stat-icon"></i>
            </div>
        </div>
    </div>
</div>

        <!-- جدول أحدث الرحلات -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 text-primary"><i class="bi bi-table"></i> أحدث الرحلات</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>م</th>
                                <th>رقم الرحلة</th>
                                <th>التاريخ</th>
                                <th>الوجهة</th>
                                <th>العمالة</th>
                                <th>السائق</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestTrips as $i => $trip)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="fw-bold">{{ $trip->flight_number }}</td>
                                    <td dir="ltr">{{ $trip->trip_date }}</td>
                                    <td> {{ $trip->city_name . ' - ' . $trip->destination }}</td>
                                    <td>{{ $trip->workers_count }}</td> <!-- أو اسم العمود الخاص بالعمالة لدك -->
                                    <td>{{ $trip->name ?? 'غير محدد' }}</td> <!-- في حال وجود علاقة مع جدول السائقين -->
                                    <td>
                                        @switch($trip->status)
                                            @case('Available')
                                                <span class="badge bg-warning text-dark"><i class="bi bi-clock"></i> متاحة (بانتظار
                                                    سائق)</span>
                                            @break

                                            @case('In_Progress')
                                                <span class="badge bg-primary"><i class="bi bi-truck"></i> جارية الآن</span>
                                            @break

                                            @case('Completed')
                                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> مكتملة</span>
                                            @break

                                            @case('Cancelled')
                                                <span class="badge bg-danger"><i class="bi bi-x-circle"></i> ملغية</span>
                                            @break

                                            @default
                                                <span class="badge bg-secondary">غير معروف</span>
                                        @endswitch
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-muted py-4">لا توجد رحلات</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endsection
