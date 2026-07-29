@extends('layouts.ticket_officer_app')

@section('content')
    <div class="main-content">
        <div class="top-header">
            <h4 class="mb-0">إدارة الرحلات</h4>
            <div>
                <a class="btn btn-primary" href="{{ route('ticket-officer.create-trip') }}">
                    <i class="bi bi-plus-lg"></i> إضافة رحلة
                </a>
            </div>
        </div>

        <div class="row">
            @foreach ($trips as $trip)
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0 {{ $trip->status == 'Available' ? 'border-warning' : 'border-primary' }} border-5">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5 class="text-primary">رقم: {{ htmlspecialchars($trip->flight_number) }}</h5>
                                <div>
                                    @switch($trip->status)
                                        @case('Available')
                                            <span class="badge bg-warning text-dark"><i class="bi bi-clock"></i> متاحة (بانتظار سائق)</span>
                                            @break
                                        @case('In_Progress')
                                            <span class="badge bg-primary"><i class="bi bi-truck"></i> جارية الآن</span>
                                            @break
                                        @case('Completed')
                                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> مكتملة</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">غير معروف</span>
                                    @endswitch

                                    <div class="py-1" dir="ltr">
                                        @if ($trip->status == 'Available')
                                            <a href="{{ route('ticket-officer.edit-trip', $trip->id) }}"
                                                class="btn btn-sm btn-warning action-btn"
                                                style="background-color: #15283F; color: white;">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            
                                            <!-- نموذج إلغاء الرحلة مع SweetAlert2 -->
                                            <form id="delete-form-{{ $trip->id }}" action="{{ route('ticket-officer.cancelled-trip', $trip->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger action-btn" onclick="confirmCancelTrip({{ $trip->id }})">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-secondary action-btn" disabled>
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button class="btn btn-sm btn-secondary action-btn" disabled>
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <p class="mt-2"><i class="bi bi-geo-alt"></i> {{ htmlspecialchars($trip->city_name . ' - ' . $trip->destination) }}</p>
                            <p><i class="bi bi-people"></i> {{ $trip->workers_count }}</p>
                            <p><i class="bi bi-person-badge"></i> السائق: <strong>{{ $trip->driver_name }}</strong></p>
                            <p class="small text-muted" dir="ltr">{{ $trip->trip_date }}</p>

                            <div class="track-stepper mt-3">
                                <div class="step {{ !empty($trip->stage_1_time) ? 'active' : '' }}">
                                    <div class="step-circle">1</div>
                                    <div class="step-label">العداد</div>
                                </div>
                                <div class="step {{ !empty($trip->stage_2_time) ? 'active' : '' }}">
                                    <div class="step-circle">2</div>
                                    <div class="step-label">المطار</div>
                                </div>
                                <div class="step {{ !empty($trip->stage_3_time) ? 'active' : '' }}">
                                    <div class="step-circle">3</div>
                                    <div class="step-label">الاستلام</div>
                                </div>
                                <div class="step {{ !empty($trip->stage_4_time) ? 'active' : '' }}">
                                    <div class="step-circle">4</div>
                                    <div class="step-label">التسليم</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if ($trips->isEmpty())
                <div class="col-12">
                    <div class="alert alert-secondary text-center">لا توجد رحلات حالية</div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<!-- تضمين مكتبة SweetAlert2 من CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmCancelTrip(tripId) {
        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: "لن يمكنك التراجع عن إجراء إلغاء هذه الرحلة!",
            icon: 'warning',
            width: '380px',
            padding: '1.5rem',
            // تصغير حجم الأيقونة والمسافات من خلال customClass
            customClass: {
                popup: 'small-swal-popup',
                icon: 'small-swal-icon',
                confirmButton: 'small-swal-btn',
                cancelButton: 'small-swal-btn'
            },
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'نعم',
            cancelButtonText: 'تراجع',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + tripId).submit();
            }
        });
    }
</script>

<!-- أكواد الـ CSS لتصغير الأيقونة -->
<style>
    .small-swal-popup {
        height: 350px;
    }
    .small-swal-icon {
        transform: scale(0.7); /* تصغير حجم الأيقونة بنسبة 30% */
        margin-bottom: 0 !important;
    }
    .small-swal-popup .swal2-title {
        font-size: 1.25rem !important;
    }
    .small-swal-popup .swal2-html-container {
        font-size: 0.9rem !important;
    }
    .small-swal-btn {
        padding: 6px 50px !important;
        border-radius: 6px !important;
    }
</style>
@endpush