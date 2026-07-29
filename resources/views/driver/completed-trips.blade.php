@extends('layouts.driver_app')

@section('title', 'رحلاتي المكتملة')

@section('content')
<div class="main-content">
    @if (!$validMonth)
        <div class="top-header">
            <h5 class="mb-0 text-success"><i class="bi bi-archive"></i> أرشيف رحلاتي المكتملة</h5>
        </div>

        <div class="row">
            @forelse ($months as $m)
                <div class="col-md-4 col-lg-3 mb-4">
                    <a href="{{ route('driver.completed-trips', ['month' => $m['ym']]) }}" class="text-decoration-none">
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
            <h5 class="mb-0 text-success"><i class="bi bi-check2-circle"></i> رحلاتي المكتملة - {{ $monthLabel }}</h5>
            <a href="{{ route('driver.completed-trips') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-right"></i> رجوع للأرشيف</a>
        </div>

        <form action="{{ route('driver.completed-trips') }}" method="GET" class="row g-2 mb-3">
            <input type="hidden" name="month" value="{{ $month }}">
            <div class="col-md-8">
                <input type="text" name="q" class="form-control" placeholder="بحث برقم الرحلة أو الوجهة..." value="{{ $q }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" type="submit"><i class="bi bi-search"></i> بحث</button>
            </div>
            @if ($q !== '')
                <div class="col-md-2">
                    <a href="{{ route('driver.completed-trips', ['month' => $month]) }}" class="btn btn-outline-secondary w-100">إلغاء الفلترة</a>
                </div>
            @endif
        </form>

        <div class="row">
            @forelse ($trips as $trip)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm border-0 border-top border-success border-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5 class="text-success">رقم: {{ $trip->flight_number }}</h5>
                                <span class="badge bg-success">مكتملة</span>
                            </div>
                            <p><i class="bi bi-geo-alt"></i> {{ $trip->destination }}</p>
                            <p><i class="bi bi-people"></i> {{ $trip->workers_count }}</p>
                            <hr>
                            <div class="small">
                                <div class="d-flex justify-content-between"><span class="text-muted">البدء:</span><span dir="ltr">{{ optional($trip->stage_1_time)->format('Y-m-d H:i') }}</span></div>
                                <div class="d-flex justify-content-between fw-bold text-success"><span>التسليم:</span><span dir="ltr">{{ optional($trip->stage_4_time)->format('Y-m-d H:i') }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-secondary text-center py-4">لا توجد نتائج مطابقة</div>
                </div>
            @endforelse
        </div>

        @if ($trips->hasPages())
            <div class="mt-3">
                {{ $trips->links() }}
            </div>
        @endif
    @endif
</div>
@endsection