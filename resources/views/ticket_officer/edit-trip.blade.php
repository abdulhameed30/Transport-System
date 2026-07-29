@extends('layouts.ticket_officer_app')

@section('content')
    <div class="main-content">
        <div class="top-header">
            <h4 class="mb-0 text-secondary">تعديل رحلة جديدة</h4>
            <a href="{{ route('ticket-officer.home') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-right"></i>
                عودة</a>
        </div>


        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('ticket-officer.update-trip',  $trip->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">تأريخ وقت الرحلة</label>
                            <input type="datetime-local" name="trip_date" class="form-control" value="{{ $trip->trip_date }}" required>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-bold">المدينة</label>
                            <select name="city_id" class="form-select">
                                <option value="">اختر...</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}" {{ $trip->city_id == $city->id ? 'selected' : '' }}>
                                        {{ $city->city_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">المطار</label>
                            <input type="text" name="destination" class="form-control" value="{{ $trip->destination }}" required dir="rtl">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">رقم الرحلة</label>
                            <input type="text" name="flight_number" class="form-control" value="{{ $trip->flight_number }}" required dir="ltr">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">عدد العمالة</label>
                            <input type="number" name="workers_count" class="form-control" value="{{ $trip->workers_count }}" required dir="ltr">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label class="form-label">ملاحظات</label>
                            <textarea name="notes" class="form-control" rows="2">{{ $trip->notes }}</textarea>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-light border">مسح</button>
                        <button type="submit" name="add_user" class="btn btn-primary"><i class="bi bi-save"></i>
                            حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
