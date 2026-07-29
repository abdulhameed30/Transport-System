@extends('layouts.manager_app')

@section('content')
    <div class="main-content">
        <div class="top-header">
            <h4 class="mb-0 text-secondary">إدارة المستخدمين</h4>
            <div>
                <a href="{{ route('manager.create-user') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i>
                    إضافة موظف</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif


        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>م</th>
                                <th>الاسم</th>
                                <th>اسم المستخدم</th>
                                <th>المهنة</th>
                                <th>المدينة</th>
                                <th>تاريخ الإضافة</th>
                                <th>إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $i => $user)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>
                                        @switch($user->role)
                                            @case('manager')
                                                <span class="badge bg-danger"><i class="bi bi-person-workspace"></i> مدير
                                                    نظام</span>
                                            @break

                                            @case('Ticket_Officer')
                                                <span class="badge bg-warning"><i class="bi bi-ticket"></i> مسؤول تذاكر</span>
                                            @break

                                            @case('Driver')
                                                <span class="badge bg-info"><i class="bi bi-truck"></i> سائق</span>
                                            @break

                                            @case('Movement_Officer')
                                                <span class="badge bg-success"><i class="bi bi-map"></i> مسؤول حركة</span>
                                            @break

                                            @default
                                                <span class="badge bg-secondary">غير محدد</span>
                                        @endswitch
                                    </td>
                                    <td>{{ $user->city_name }}</td>
                                    <td>{{ $user->created_at }}</td>
                                    <td>
                                        <a href="{{ route('manager.edit-user', $user->id) }}"
                                            class="btn btn-sm btn-warnin  action-btn  "
                                            style="background-color: #15283F; color: white;"><i
                                                class="bi bi-pencil-square"></i></a>

                                        @if (session('user_id') == $user->id)
                                            <button class="btn btn-sm btn-secondary action-btn" disabled>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @else
                                            <form action="{{ route('manager.users.destroy', $user->id) }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger action-btn"
                                                    onclick="return confirm('هل أنت متأكد من حذف المستخدم؟')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif

                                    </td>
                                </tr>
                                @empty


                                    <tr>
                                        <td colspan="6" class="text-muted py-4">لا يوجد موظفين</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endsection
