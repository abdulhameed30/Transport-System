@extends('layouts.manager_app')

@section('content')

<div class="main-content">

    <div class="top-header">
        <h4 class="mb-0 text-secondary">
            تعديل موظف
        </h4>

        <a href="{{ route('manager.users') }}" 
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right"></i>
            عودة
        </a>
    </div>


    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">
            @error('username')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            <form method="POST" 
                  action="{{ route('manager.users.update', $user->id) }}">

                @csrf
                @method('PUT')


                <div class="row">


                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            الاسم الكامل
                        </label>

                        <input 
                            type="text"
                            name="name"
                            class="form-control"
                            value="{{ old('name',$user->name) }}"
                            required>

                    </div>



                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            اسم المستخدم
                        </label>

                        <input 
                            type="text"
                            name="username"
                            class="form-control"
                            value="{{ old('username',$user->username) }}"
                            required
                            dir="ltr">

                    </div>


                </div>




                <div class="row">


                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            كلمة المرور
                        </label>

                        <input 
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="اتركها فارغة إذا لم تريد تغييرها"
                            dir="ltr">

                    </div>




                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            المدينة
                        </label>


                        <select name="city_id" class="form-select">


                            <option value="">
                                اختر المدينة
                            </option>


                            @foreach($cities as $city)

                                <option value="{{ $city->id }}"
                                    {{ $user->city_id == $city->id ? 'selected' : '' }}>

                                    {{ $city->city_name }}

                                </option>

                            @endforeach


                        </select>

                    </div>


                </div>





                <div class="row">


                    <div class="col-md-6 mb-4">

                        <label class="form-label fw-bold">
                            المهنة
                        </label>


                        <select name="role" 
                                class="form-select"
                                required>


                            <option value="">
                                اختر المهنة
                            </option>


                            <option value="manager"
                                {{ $user->role == 'manager' ? 'selected' : '' }}>
                                مدير
                            </option>



                            <option value="Ticket_Officer"
                                {{ $user->role == 'Ticket_Officer' ? 'selected' : '' }}>
                                مسؤول تذاكر
                            </option>



                            <option value="Driver"
                                {{ $user->role == 'Driver' ? 'selected' : '' }}>
                                سائق
                            </option>



                            <option value="Movement_Officer"
                                {{ $user->role == 'Movement_Officer' ? 'selected' : '' }}>
                                مسؤول حركة
                            </option>


                        </select>

                    </div>


                </div>




                <div class="d-flex justify-content-end gap-2">


                    <a href="{{ route('manager.users') }}"
                       class="btn btn-light border">

                        إلغاء

                    </a>



                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-save"></i>
                        حفظ التعديلات

                    </button>


                </div>



            </form>

        </div>

    </div>


</div>

@endsection