@extends('layouts.movement_officer_app') {{-- افتراض وجود تخطيط خاص بمسبؤول الحركة --}}


@section('content')
<div class="main-content">
    <div class="top-header">
        <h4 class="mb-0"><i class="bi bi-broadcast text-primary"></i> المراقبة المباشرة</h4>
        <div class="user-info">
                <i class="bi bi-person-circle fs-5"></i>
                <span class="fw-bold mx-2">{{ session('name') }}</span>
            </div>
    </div>

    @forelse ($trips as $trip)
        @php 
            // حساب مستوى التقدم اعتماداً على الأوقات المسجلة
            $level = 0;
            if ($trip->stage_4_time) $level = 4;
            elseif ($trip->stage_3_time) $level = 3;
            elseif ($trip->stage_2_time) $level = 2;
            elseif ($trip->stage_1_time) $level = 1;
        @endphp

        <div class="card shadow-sm border-0 border-start {{ $trip->status == 'Available' ? 'border-warning' : 'border-primary' }} border-5 mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3 border-end">
                        <h5 class="text-primary">رقم: {{ $trip->flight_number }}</h5>
                        <div><i class="bi bi-geo-alt"></i> {{ $trip->destination }}</div>
                        <div><i class="bi bi-people"></i> {{ $trip->workers_count }}</div>
                        @if ($trip->status == 'In_Progress')
                            <div class="fw-bold"><i class="bi bi-steering-wheel"></i> السائق: {{ $trip->driver_name  }}</div>
                        @else
                            <span class="badge bg-warning text-dark">بانتظار سائق</span>
                        @endif
                    </div>
                    
                    <div class="col-md-7">
                        @if ($trip->status == 'In_Progress')
                            <div class="track-stepper">
                                <div class="step {{ $level >= 1 ? 'active' : '' }}">
                                    <div class="step-circle"><i class="bi bi-camera"></i></div>
                                    <div class="step-label">بدء</div>
                                    <span class="time-label" dir="ltr">{{ $level >= 1 && $trip->stage_1_time ? optional($trip->stage_1_time)->format('h:i A') : '--:--' }}</span>
                                </div>
                                <div class="step {{ $level >= 2 ? 'active' : '' }}">
                                    <div class="step-circle"><i class="bi bi-airplane"></i></div>
                                    <div class="step-label">المطار</div>
                                    <span class="time-label" dir="ltr">{{ $level >= 2 && $trip->stage_2_time ? optional($trip->stage_2_time)->format('h:i A') : '--:--' }}</span>
                                </div>
                                <div class="step {{ $level >= 3 ? 'active' : '' }}">
                                    <div class="step-circle"><i class="bi bi-box-arrow-in-down"></i></div>
                                    <div class="step-label">الاستلام</div>
                                    <span class="time-label" dir="ltr">{{ $level >= 3 && $trip->stage_3_time ? optional($trip->stage_3_time)->format('h:i A') : '--:--' }}</span>
                                </div>
                                <div class="step {{ $level >= 4 ? 'active' : '' }}">
                                    <div class="step-circle"><i class="bi bi-flag"></i></div>
                                    <div class="step-label">التسليم</div>
                                    <span class="time-label" dir="ltr">{{ $level >= 4 && $trip->stage_4_time ? optional($trip->stage_4_time)->format('h:i A') : '--:--' }}</span>
                                </div>
                            </div>
                        @else
                            <div class="text-center text-muted py-3">
                                <i class="bi bi-hourglass-split fs-2 d-block"></i>الرحلة مجدولة ولم تبدأ
                            </div>
                        @endif
                    </div>

                    <div class="col-md-2 text-center border-start">
                        @if (!empty($trip->odometer_image))
                            <button class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#imgModal{{ $trip->id }}">
                                <i class="bi bi-image"></i> صورة العداد
                            </button>
                            
                            <!-- Modal عرض صورة العداد -->
                            <div class="modal fade" id="imgModal{{ $trip->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-body p-0">
                                            {{-- استخدام asset للوصول للصورة في التخزين العام لـ Laravel --}}
                                            <img src="{{ asset('upload/odometer_images/' . $trip->odometer_image) }}" class="img-fluid w-100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <span class="text-muted small">لا توجد صورة</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-secondary text-center">لا توجد رحلات نشطة للمراقبة</div>
    @endforelse
</div>
@endsection