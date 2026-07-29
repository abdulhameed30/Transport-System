@extends('layouts.driver_app')

@section('content')
    <div class="main-content">
        <div class="top-header">
            <h5 class="mb-0">أهلاً بك كابتن <span class="text-danger">{{ session('name') }}</span></h5>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($activeTrip)
            <div class="card border-0 shadow-sm rounded-4 border-primary border-5 mb-4">
                <div class="card-body p-4">
                    <h4 class="text-primary">الرحلة الحالية</h4>
                    <div class="row">
                        <div class="col-6"><strong>الوجهة:</strong> {{ $activeTrip->destination }}</div>
                        <div class="col-6"><strong>رقم الرحلة:</strong> {{ $activeTrip->flight_number }}</div>
                        <div class="col-6"><strong>العمالة:</strong> {{ $activeTrip->workers_count }}</div>
                    </div>
                    @if ($activeTrip->notes)
                        <div class="alert alert-light small mt-2">{{ $activeTrip->notes }}</div>
                    @endif

                    <h5 class="mt-4">مراحل الرحلة</h5>

                    <!-- المرحلة 1 (مكتملة) -->
                    <div class="btn btn-success w-100 stage-btn mb-2" disabled>
                        <span><i class="bi bi-check-circle-fill"></i> 1. بدء الرحلة (العداد)</span>
                        <span dir="ltr">{{ optional($activeTrip->stage_1_time)->format('Y-m-d H:i') }}</span>
                    </div>

                    <!-- المرحلة 2 -->
                    <form action="{{ route('driver.update-stage') }}" method="POST">
                        @csrf
                        <input type="hidden" name="trip_id" value="{{ $activeTrip->id }}">
                        <input type="hidden" name="stage" value="2">
                        @if (empty($activeTrip->stage_2_time))
                            <button type="submit" class="btn btn-primary w-100 stage-btn mb-2">
                                <span><i class="bi bi-geo-alt"></i> 2. الوصول للمطار</span>
                                <i class="bi bi-arrow-left-circle"></i>
                            </button>
                        @else
                            <div class="btn btn-success w-100 stage-btn mb-2" disabled>
                                <span><i class="bi bi-check-circle-fill"></i> 2. تم الوصول</span>
                                <span dir="ltr">{{ optional($activeTrip->stage_2_time)->format('Y-m-d H:i') }}</span>
                            </div>
                        @endif
                    </form>

                    <!-- المرحلة 3 -->
                    <form action="{{ route('driver.update-stage') }}" method="POST">
                        @csrf
                        <input type="hidden" name="trip_id" value="{{ $activeTrip->id }}">
                        <input type="hidden" name="stage" value="3">
                        @if (!empty($activeTrip->stage_2_time) && empty($activeTrip->stage_3_time))
                            <button type="submit" class="btn btn-primary w-100 stage-btn mb-2">
                                <span><i class="bi bi-people"></i> 3. استلام العمالة</span>
                                <i class="bi bi-arrow-left-circle"></i>
                            </button>
                        @elseif (!empty($activeTrip->stage_3_time))
                            <div class="btn btn-success w-100 stage-btn mb-2" disabled>
                                <span><i class="bi bi-check-circle-fill"></i> 3. تم الاستلام</span>
                                <span dir="ltr">{{ optional($activeTrip->stage_3_time)->format('Y-m-d H:i') }}</span>
                            </div>
                        @else
                            <div class="btn btn-secondary w-100 stage-btn mb-2" disabled>3. بانتظار الوصول للمطار</div>
                        @endif
                    </form>

                    <!-- المرحلة 4 -->
                    <form action="{{ route('driver.update-stage') }}" method="POST">
                        @csrf
                        <input type="hidden" name="trip_id" value="{{ $activeTrip->id }}">
                        <input type="hidden" name="stage" value="4">
                        @if (!empty($activeTrip->stage_3_time) && empty($activeTrip->stage_4_time))
                            <button type="submit" class="btn btn-warning w-100 stage-btn text-dark"
                                onclick="return confirm('إنهاء الرحلة؟')">
                                <span><i class="bi bi-flag"></i> 4. إنهاء الرحلة (تسليم)</span>
                                <i class="bi bi-arrow-left-circle"></i>
                            </button>
                        @elseif (empty($activeTrip->stage_4_time))
                            <div class="btn btn-secondary w-100 stage-btn" disabled>4. بانتظار الاستلام</div>
                        @endif
                    </form>
                </div>
            </div>
        @else
            <div class="row">
                @forelse ($availableTrips as $trip)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body">
                                <h5 class="card-title text-primary">رقم: {{ $trip->flight_number }}</h5>
                                <p><i class="bi bi-geo-alt"></i> {{ $trip->destination }}</p>
                                <p><i class="bi bi-people"></i> {{ $trip->workers_count }}</p>
                                <p class="small text-muted" dir="ltr">
                                    {{ optional($trip->trip_date)->format('Y-m-d H:i') }}</p>
                                <button class="btn btn-primary w-100" data-bs-toggle="modal"
                                    data-bs-target="#startModal{{ $trip->id }}">
                                    <i class="bi bi-camera"></i> تصوير العداد وبدء
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="startModal{{ $trip->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title">بدء الرحلة {{ $trip->flight_number }}</h5>
                                    <button type="button" class="btn-close btn-close-white"
                                        data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('driver.start-trip') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body text-center">
                                        <input type="hidden" name="trip_id" value="{{ $trip->id }}">
                                        <i class="bi bi-speedometer2 display-1 text-secondary"></i>
                                        <p>التقط صورة للعداد</p>
                                        <input type="file" name="odometer_image" class="form-control form-control-lg"
                                            accept="image/*" capture="environment" required>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">إلغاء</button>
                                        <button type="submit" class="btn btn-success">تأكيد وبدء</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-secondary text-center">لا توجد رحلات متاحة حالياً.</div>
                    </div>
                @endforelse
            </div>
        @endif
\    </div>
@endsection
