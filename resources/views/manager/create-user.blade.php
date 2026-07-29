@extends('layouts.manager_app')

@section('content')
    <div class="main-content">
        <div class="top-header">
            <h4 class="mb-0 text-secondary">إضافة موظف جديد</h4>
            <a href="{{ route('manager.users') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-right"></i> عودة</a>
        </div>


        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                @error('username')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <form method="POST" action="{{ route('manager.users.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">الاسم الكامل</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">اسم المستخدم</label>
                            <input type="text" name="username" class="form-control" required dir="ltr">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">كلمة المرور</label>
                            <input type="password" name="password" class="form-control" required dir="ltr">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">المدينة</label>
                            <select name="city_id" class="form-select">
                                <option value="">اختر...</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}">
                                        {{ $city->city_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">المهنة</label>
                            <select name="role" class="form-select" required>
                                <option value="">اختر...</option>
                                <option value="Manager">مدير</option>
                                <option value="Ticket_Officer">مسؤول تذاكر</option>
                                <option value="Driver">سائق</option>
                                <option value="Movement_Officer">مسؤول حركة</option>
                            </select>
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
