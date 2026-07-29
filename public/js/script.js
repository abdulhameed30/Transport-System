document.addEventListener('DOMContentLoaded', function () {
    var sidebar = document.getElementById('sidebar');
    if (!sidebar) return;

    var overlay = document.getElementById('sidebarOverlay');
    var toggleBtn = document.getElementById('sidebarToggleBtn');
    var collapseBtn = document.getElementById('sidebarCollapseBtn');
    var body = document.body;

    /* ===== وضع الجوال: قائمة منسدلة فوق المحتوى ===== */
    function openMobileSidebar() {
        sidebar.classList.add('show');
        if (overlay) overlay.classList.add('show');
    }
    function closeMobileSidebar() {
        sidebar.classList.remove('show');
        if (overlay) overlay.classList.remove('show');
    }
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            if (sidebar.classList.contains('show')) {
                closeMobileSidebar();
            } else {
                openMobileSidebar();
            }
        });
    }
    if (overlay) {
        overlay.addEventListener('click', closeMobileSidebar);
    }
    sidebar.querySelectorAll('.nav-link').forEach(function (link) {
        link.addEventListener('click', function () {
            if (window.innerWidth < 992) closeMobileSidebar();
        });
    });
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 992) closeMobileSidebar();
    });

    /* ===== وضع سطح المكتب: طي / بسط الشريط الجانبي ===== */
    function applyCollapsedState(collapsed) {
        body.classList.toggle('sidebar-collapsed', collapsed);
        if (collapseBtn) {
            var icon = collapseBtn.querySelector('i');
            if (icon) icon.className = collapsed ? 'bi bi-chevron-left' : 'bi bi-chevron-right';
        }
    }

    var savedState = localStorage.getItem('sidebarCollapsed') === '1';
    applyCollapsedState(savedState);

    if (collapseBtn) {
        collapseBtn.addEventListener('click', function () {
            var collapsed = !body.classList.contains('sidebar-collapsed');
            applyCollapsedState(collapsed);
            localStorage.setItem('sidebarCollapsed', collapsed ? '1' : '0');
        });
    }

    /* ===== تلميحات عند الطي: عرض اسم الرابط كـ title ===== */
    sidebar.querySelectorAll('.nav-link').forEach(function (link) {
        var textEl = link.querySelector('.nav-text');
        if (textEl) link.setAttribute('title', textEl.textContent.trim());
    });
});
