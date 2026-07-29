<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JADWA</title>
    <!-- قم بروابط ملفات الـ CSS الخاصة بك هنا، مثلاً باستخدام Vite -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="{{ asset('js/script.js') }}"></script>
    <link rel="icon" href="{{ asset('upload/logo.jpeg') }}" type="image/x-icon">
</head>

<body>


    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <button class="sidebar-toggle-btn" id="sidebarToggleBtn" type="button" aria-label="فتح القائمة">
        <i class="bi bi-list"></i>
    </button>
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <a href="#" class="brand-logo">
                <img src="{{ asset('upload/logo.jpeg') }}" alt="Jadwa - جدوى للاستقدام" class="brand-logo-img">
                <span class="brand-text">
                    <strong>جدوى للاستقدام</strong>
                    <small>نظام إدارة النقل</small>
                </span>
            </a>
            <button class="sidebar-collapse-btn d-none d-lg-flex" id="sidebarCollapseBtn" type="button"
                aria-label="طي القائمة">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>

        <div class="sidebar-user">
            <div class="sidebar-user-avatar"><i class="bi bi-person"></i></div>
            <div class="sidebar-user-info">
                <strong>{{ session('name') }}</strong>
                <span class="sidebar-user-role">سائق </span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <span class="sidebar-nav-label">القائمة الرئيسية</span>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link  {{ request()->routeIs('driver.home') ? 'active' : '' }}"
                        href="{{ route('driver.home') }}">
                        <span class="nav-icon"><i class="bi bi-speedometer2"></i></span>
                        <span class="nav-text">الرحلات الحالية</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{ request()->routeIs('driver.completed-trips') ? 'active' : '' }}"
                        href="{{ route('driver.completed-trips') }}">
                        <span class="nav-icon"><i class="bi bi-check2-all"></i></span>
                        <span class="nav-text">رحلاتي المكتملة</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="sidebar-footer">
            <a class="nav-link logout-link" href="{{ route('logout') }}">
                <span class="nav-icon"><i class="bi bi-box-arrow-right"></i></span>
                <span class="nav-text">تسجيل الخروج</span>
            </a>
        </div>
    </aside>


    <main class="flex-1 mr-64 p-8">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
