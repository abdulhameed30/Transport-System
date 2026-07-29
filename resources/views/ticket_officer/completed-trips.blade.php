@extends('layouts.ticket_officer_app') {{-- أو التخطيط الخاص بك --}}


@section('content')
<div class="main-content">
    @if (!$validMonth)
        <div class="top-header">
            <h4 class="text-success mb-0"><i class="bi bi-archive"></i> أرشيف الرحلات المكتملة</h4>
        </div>

        <div class="row">
            @forelse ($months as $m)
                <div class="col-md-4 col-lg-3 mb-4">
                    <a href="{{ route('ticket-officer.completed-trips', ['month' => $m['ym']]) }}" class="text-decoration-none">
                        <div class="card stat-card bg-white border shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-calendar-month fs-1 text-primary d-block mb-2"></i>
                                <h6 class="text-dark mb-1">{{ $m['label'] }}</h6>
                                <span class="badge bg-success">{{ $m['count'] }} رحلة</span>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-secondary text-center py-4">لا توجد رحلات مكتملة بعد</div>
                </div>
            @endforelse
        </div>

    @else
        <div class="top-header">
            <h4 class="text-success mb-0"><i class="bi bi-check2-all"></i> رحلات {{ $monthLabel }}</h4>
            <a href="{{ route('ticket-officer.completed-trips') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-right"></i> رجوع للأرشيف
            </a>
        </div>

        <form action="{{ route('ticket-officer.completed-trips') }}" method="GET" class="row g-2 mb-3">
            <input type="hidden" name="month" value="{{ $month }}">
            <div class="col-md-8">
                <input type="text" name="q" class="form-control" placeholder="بحث برقم الرحلة أو الوجهة أو اسم السائق..." value="{{ $q }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" type="submit"><i class="bi bi-search"></i> بحث</button>
            </div>
            @if ($q !== '')
                <div class="col-md-2">
                    <a href="{{ route('ticket-officer.completed-trips', ['month' => $month]) }}" class="btn btn-outline-secondary w-100">إلغاء الفلترة</a>
                </div>
            @endif
        </form>

        <div class="card border-0 shadow-sm rounded-4">
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
                                <th>وقت التسليم</th>
                                <th>تفاصيل</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($trips as $i=> $trip)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="fw-bold text-primary">{{ $trip->flight_number }}</td>
                                    <td dir="ltr">{{ optional($trip->trip_date)->format('Y-m-d H:i') }}</td>
                                    <td>{{ $trip->destination }}</td>
                                    <td>{{ $trip->workers_count }}</td>
                                    <td>{{ $trip->driver_name ?? 'غير معروف' }}</td>
                                    <td>
                                    @if ($trip->status == 'Completed')
                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i>  مكتملة</span>
                                    @elseif ($trip->status == 'Cancelled')
                                        <span class="badge bg-danger"><i class="bi bi-x-circle"></i> ملغية</span>
                                    @endif
                                    </td>
                                    <td dir="ltr" class="text-success">{{ optional($trip->stage_4_time)->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#timeline{{ $trip->id }}">
                                            <i class="bi bi-clock-history"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal التفاصيل -->
                                <div class="modal fade" id="timeline{{ $trip->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-dark">
                                                <h5 class="modal-title">تفاصيل الأوقات: {{ $trip->flight_number }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-start">
                                                <ul class="list-unstyled">
                                                    <li><strong>بدء الرحلة:</strong> <span dir="ltr">{{ optional($trip->stage_1_time)->format('Y-m-d H:i') ?: '—' }}</span></li>
                                                    <li><strong>الوصول للمطار:</strong> <span dir="ltr">{{ optional($trip->stage_2_time)->format('Y-m-d H:i') ?: '—' }}</span></li>
                                                    <li><strong>استلام العمالة:</strong> <span dir="ltr">{{ optional($trip->stage_3_time)->format('Y-m-d H:i') ?: '—' }}</span></li>
                                                    <li><strong>التسليم النهائي:</strong> <span dir="ltr">{{ optional($trip->stage_4_time)->format('Y-m-d H:i') ?: '—' }}</span></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-muted py-4">لا توجد نتائج مطابقة</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if ($trips->hasPages())
            <div class="mt-3">
                {{ $trips->links() }}
            </div>
        @endif
    @endif
</div>
@endsection