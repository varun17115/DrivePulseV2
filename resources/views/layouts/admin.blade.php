<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Immediate Theme Setup to prevent FOUC (Flash of unstyled content) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>

    <title>@yield('title', 'Dashboard') | DrivePulse v2.0</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 Free -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables BS5 CSS -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">

    <!-- Select2 BS5 Theme -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <!-- Flatpickr -->
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            /* Colors */
            --bs-primary: #4f46e5;
            --bs-primary-rgb: 79, 70, 229;
            --bs-secondary: #64748b;
            --bs-success: #10b981;
            --bs-danger: #ef4444;
            --bs-warning: #f59e0b;
            --bs-info: #0ea5e9;

            --bg-body: #f8fafc;
            --sidebar-bg: #0f172a;
            --sidebar-text: #cbd5e1;
            --sidebar-hover: #1e293b;

            --card-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.04), 0 0 3px rgba(0, 0, 0, 0.02);
        }

        /* Modern Dark Mode Tokens & Overrides */
        [data-bs-theme="dark"] {
            --bg-body: #0b0f19;
            --sidebar-bg: #030712;
            --sidebar-hover: #111827;
            --sidebar-text: #94a3b8;
            --card-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.5);
        }

        [data-bs-theme="dark"] body {
            background-color: #0b0f19 !important;
            color: #e2e8f0 !important;
        }

        [data-bs-theme="dark"] .top-navbar {
            background-color: rgba(17, 24, 39, 0.95) !important;
            border-color: #1f2937 !important;
        }

        [data-bs-theme="dark"] .card {
            background-color: #111827 !important;
            border-color: #1f2937 !important;
            color: #e2e8f0;
        }

        [data-bs-theme="dark"] .card-header {
            background-color: #111827 !important;
            border-color: #1f2937 !important;
        }

        [data-bs-theme="dark"] .card-footer {
            background-color: #111827 !important;
            border-color: #1f2937 !important;
        }

        [data-bs-theme="dark"] .bg-light,
        [data-bs-theme="dark"] .bg-white {
            background-color: #1f2937 !important;
            color: #e2e8f0 !important;
        }

        [data-bs-theme="dark"] .table {
            color: #cbd5e1 !important;
            border-color: #1f2937 !important;
        }

        [data-bs-theme="dark"] .table > thead {
            background-color: #1a2234 !important;
            color: #94a3b8 !important;
        }

        [data-bs-theme="dark"] .table > tbody > tr > td {
            color: #cbd5e1 !important;
        }

        [data-bs-theme="dark"] .text-dark {
            color: #f8fafc !important;
        }

        [data-bs-theme="dark"] .text-secondary,
        [data-bs-theme="dark"] .text-muted {
            color: #94a3b8 !important;
        }

        [data-bs-theme="dark"] .form-control,
        [data-bs-theme="dark"] .form-select {
            background-color: #1f2937 !important;
            border-color: #374151 !important;
            color: #f8fafc !important;
        }

        [data-bs-theme="dark"] .form-control:focus,
        [data-bs-theme="dark"] .form-select:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25) !important;
        }

        [data-bs-theme="dark"] .dropdown-menu {
            background-color: #111827 !important;
            border: 1px solid #374151 !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.6) !important;
        }

        [data-bs-theme="dark"] .dropdown-item {
            color: #cbd5e1 !important;
        }

        [data-bs-theme="dark"] .dropdown-item:hover {
            background-color: #1f2937 !important;
            color: #ffffff !important;
        }

        [data-bs-theme="dark"] .modal-content {
            background-color: #111827 !important;
            border-color: #374151 !important;
            color: #e2e8f0 !important;
        }

        [data-bs-theme="dark"] .border-secondary-subtle,
        [data-bs-theme="dark"] .border-light,
        [data-bs-theme="dark"] .border {
            border-color: #1f2937 !important;
        }

        [data-bs-theme="dark"] .btn-light {
            background-color: #1f2937 !important;
            border-color: #374151 !important;
            color: #cbd5e1 !important;
        }

        [data-bs-theme="dark"] .btn-light:hover {
            background-color: #374151 !important;
            color: #ffffff !important;
        }

        [data-bs-theme="dark"] .list-group-item {
            background-color: #111827 !important;
            border-color: #1f2937 !important;
            color: #cbd5e1 !important;
        }

        [data-bs-theme="dark"] .nav-tabs .nav-link {
            color: #94a3b8;
            border-color: transparent;
        }

        [data-bs-theme="dark"] .nav-tabs .nav-link.active {
            background-color: #111827 !important;
            border-color: #374151 #374151 #111827 !important;
            color: #f8fafc !important;
        }

        [data-bs-theme="dark"] .nav-tabs {
            border-bottom-color: #374151 !important;
        }

        [data-bs-theme="dark"] .dataTables_wrapper .dataTables_length select,
        [data-bs-theme="dark"] .dataTables_wrapper .dataTables_filter input {
            background-color: #1f2937 !important;
            border-color: #374151 !important;
            color: #f8fafc !important;
        }

        [data-bs-theme="dark"] .page-link {
            background-color: #1f2937 !important;
            border-color: #374151 !important;
            color: #cbd5e1 !important;
        }

        [data-bs-theme="dark"] .page-item.disabled .page-link {
            background-color: #111827 !important;
            border-color: #1f2937 !important;
            color: #64748b !important;
        }

        [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-selection {
            background-color: #1f2937 !important;
            border-color: #374151 !important;
            color: #f8fafc !important;
        }

        [data-bs-theme="dark"] .select2-dropdown {
            background-color: #111827 !important;
            border-color: #374151 !important;
            color: #f8fafc !important;
        }

        [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-dropdown .select2-results__option--highlighted {
            background-color: #4f46e5 !important;
        }

        [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            color: #f8fafc !important;
        }

        [data-bs-theme="dark"] .swal2-popup {
            background: #111827 !important;
            color: #e2e8f0 !important;
            border: 1px solid #374151 !important;
        }

        [data-bs-theme="dark"] .swal2-title,
        [data-bs-theme="dark"] .swal2-html-container {
            color: #f8fafc !important;
        }

        [data-bs-theme="dark"] .flatpickr-calendar {
            background: #111827 !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.6) !important;
            border: 1px solid #374151 !important;
        }

        [data-bs-theme="dark"] .flatpickr-day {
            color: #e2e8f0 !important;
        }

        [data-bs-theme="dark"] .flatpickr-day.nextMonthDay,
        [data-bs-theme="dark"] .flatpickr-day.prevMonthDay {
            color: #475569 !important;
        }

        [data-bs-theme="dark"] .flatpickr-current-month,
        [data-bs-theme="dark"] .flatpickr-weekday {
            color: #f8fafc !important;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: #334155;
            font-size: 0.9rem;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Typography & Utilities */
        .text-2xs { font-size: 0.7rem; }
        .text-3xs { font-size: 0.65rem; }
        .fs-7 { font-size: 0.95rem !important; }
        .fs-8 { font-size: 0.85rem !important; }

        .text-primary-gradient {
            background: linear-gradient(135deg, #4f46e5, #0ea5e9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hover-primary:hover { color: var(--bs-primary) !important; }

        /* Main Layout Grid */
        .app-wrapper {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* Sidebar Styling */
        .sidebar-wrapper {
            width: 270px;
            background-color: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            z-index: 1040;
        }

        .brand-icon-box {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #4f46e5, #0ea5e9);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
        }

        .sidebar-user-card {
            background-color: var(--sidebar-hover);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-nav-container {
            flex-grow: 1;
            overflow-y: auto;
        }

        .nav-link {
            color: var(--sidebar-text);
            border-radius: 8px;
            padding: 0.65rem 1rem;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .nav-link:hover, .nav-link:focus {
            color: #fff;
            background-color: var(--sidebar-hover);
        }

        .nav-link.active {
            color: #fff;
            background-color: var(--bs-primary);
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
        }

        .nav-icon {
            width: 24px;
            font-size: 1.1rem;
            margin-right: 12px;
            color: inherit;
            opacity: 0.8;
            text-align: center;
        }

        .nav-link.active .nav-icon { opacity: 1; }

        /* Main Content Container */
        .main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            position: relative;
        }

        /* Top Navbar */
        .top-navbar {
            box-shadow: 0 4px 20px -2px rgba(0,0,0,0.03);
            z-index: 1030;
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.95) !important;
        }

        .btn-icon {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .icon-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        /* Cards */
        .card {
            border: none;
            box-shadow: var(--card-shadow);
            border-radius: 12px;
            margin-bottom: 1.5rem;
            transition: all 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 10px 25px -4px rgba(0, 0, 0, 0.06);
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1.25rem 1.5rem;
            border-top-left-radius: 12px !important;
            border-top-right-radius: 12px !important;
        }

        .card-body { padding: 1.5rem; }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.5);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(107, 114, 128, 0.8); }

        /* Notification Pulse */
        .notification-pulse {
            animation: pulse-ring 2s infinite;
        }

        @keyframes pulse-ring {
            0% { transform: scale(0.8) translate(-50%, -50%); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { transform: scale(1) translate(-50%, -50%); box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); }
            100% { transform: scale(0.8) translate(-50%, -50%); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }

        /* DataTables Customization */
        table.dataTable { border-collapse: collapse !important; }
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 6px;
            border: 1px solid #dee2e6;
            padding: 0.375rem 0.75rem;
            font-size: 0.9rem;
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: var(--bs-primary);
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.25);
        }
        .page-item.active .page-link { background-color: var(--bs-primary); border-color: var(--bs-primary); }
        .table > :not(caption) > * > * { padding: 1rem 0.75rem; vertical-align: middle; }
        .table > thead { background-color: #f8fafc; font-size: 0.8rem; text-transform: uppercase; color: #64748b; letter-spacing: 0.5px; }
        .table > tbody > tr > td { font-size: 0.9rem; color: #475569; }

        /* Mobile Sidebar toggle */
        @media (max-width: 991.98px) {
            .sidebar-wrapper {
                position: fixed;
                left: -270px;
                height: 100vh;
            }
            .sidebar-wrapper.show {
                left: 0;
                box-shadow: 10px 0 30px rgba(0,0,0,0.5);
            }
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 1035;
                backdrop-filter: blur(2px);
            }
            .sidebar-overlay.show { display: block; }
        }
    </style>

    @stack('styles')
    @yield('styles')
</head>
<body>

    <div class="app-wrapper">
        <!-- Sidebar -->
        @include('layouts.partials.sidebar')
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Main Content Wrapper -->
        <main class="main-content custom-scrollbar" id="mainContent">
            <!-- Navbar -->
            @include('layouts.partials.navbar')

            <!-- Page Content -->
            <div class="container-fluid p-4 flex-grow-1">
                @yield('content')
            </div>

            <!-- Footer -->
            @include('layouts.partials.footer')
        </main>
    </div>

    <!-- Core Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Plugins -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Global App Script -->
    <script>
        $(document).ready(function() {
            // CSRF Setup for AJAX
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            // Sidebar Toggle
            $('#sidebarToggleBtn, #sidebarCloseBtn, #sidebarOverlay').on('click', function() {
                $('#sidebar').toggleClass('show');
                $('#sidebarOverlay').toggleClass('show');
            });

            // Initialize Tooltips & Popovers
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

            // Setup DataTables global defaults
            $.extend(true, $.fn.dataTable.defaults, {
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records...",
                    lengthMenu: "_MENU_ per page",
                    paginate: {
                        previous: '<i class="fa-solid fa-chevron-left"></i>',
                        next: '<i class="fa-solid fa-chevron-right"></i>'
                    }
                },
                pageLength: 10,
                responsive: true,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center'l><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'f>>" +
                     "<'row'<'col-sm-12'tr>>" +
                     "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 d-flex justify-content-end'p>>"
            });

            // Initialize Select2 globally
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            // Initialize Flatpickr globally
            $('.flatpickr').flatpickr({
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });

            // SweetAlert Confirm Delete globally
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                let form = $(this).closest('form');
                let confirmText = $(this).data('confirm') || 'Are you sure you want to delete this record?';

                Swal.fire({
                    title: 'Are you sure?',
                    text: confirmText,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, delete it!',
                    borderRadius: '12px'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // Handle Session Flashes via SweetAlert Toast
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });

            @if(session('success'))
                Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
            @endif

            @if(session('error'))
                Toast.fire({ icon: 'error', title: "{{ session('error') }}" });
            @endif

            @if(session('warning'))
                Toast.fire({ icon: 'warning', title: "{{ session('warning') }}" });
            @endif

            // ================= THEME TOGGLE (DARK / LIGHT MODE) =================
            function updateThemeUI(theme) {
                document.documentElement.setAttribute('data-bs-theme', theme);
                localStorage.setItem('theme', theme);

                if (theme === 'dark') {
                    $('.theme-icon-moon').addClass('d-none');
                    $('.theme-icon-sun').removeClass('d-none');
                    $('#themeToggleBtn').attr('title', 'Switch to Light Mode');
                } else {
                    $('.theme-icon-moon').removeClass('d-none');
                    $('.theme-icon-sun').addClass('d-none');
                    $('#themeToggleBtn').attr('title', 'Switch to Dark Mode');
                }
            }

            // Sync initial state icon with document theme
            const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
            updateThemeUI(currentTheme);

            $(document).on('click', '#themeToggleBtn', function() {
                const active = document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
                updateThemeUI(active);
            });
        });
    </script>

    @stack('scripts')

</body>
</html>
