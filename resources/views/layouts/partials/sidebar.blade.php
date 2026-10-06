@php
    $user = auth()->user();
    $isAdmin = $user->hasRole('admin');
    $isTrainer = $user->hasRole('trainer');
    $isStudent = $user->hasRole('student');
    $currentRoute = Route::currentRouteName();
@endphp

<aside class="sidebar-wrapper" id="sidebar">
    <!-- Brand Header -->
    <div class="sidebar-header d-flex align-items-center justify-content-between px-4 py-3">
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none brand-logo">
            <div class="brand-icon-box me-3">
                <i class="fa-solid fa-car-side text-white"></i>
            </div>
            <div>
                <span class="brand-title fw-bold">Drive<span class="text-primary-gradient">Pulse</span></span>
                <span class="badge bg-primary-subtle text-primary ms-1 px-1.5 py-0.5 rounded-pill text-2xs fw-bold">v2.0</span>
            </div>
        </a>
        <button class="btn btn-sm btn-icon d-lg-none text-white-50 hover-text-white" id="sidebarCloseBtn">
            <i class="fa-solid fa-xmark fs-5"></i>
        </button>
    </div>

    <!-- User Quick Card in Sidebar -->
    <div class="sidebar-user-card mx-3 my-2 p-3 rounded-4">
        <div class="d-flex align-items-center">
            <div class="position-relative me-3">
                <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=6366f1&color=fff&bold=true' }}"
                     alt="{{ $user->name }}"
                     class="rounded-circle avatar-img border border-2 border-primary-subtle" width="44" height="44">
                <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-dark rounded-circle">
                    <span class="visually-hidden">Online</span>
                </span>
            </div>
            <div class="flex-grow-1 overflow-hidden">
                <h6 class="text-white text-truncate mb-0 fw-semibold fs-7">{{ $user->name }}</h6>
                <div class="d-flex align-items-center gap-1 mt-1">
                    @if($isAdmin)
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill text-3xs fw-bold px-2 py-0.5">
                            <i class="fa-solid fa-shield-halved me-1"></i>Administrator
                        </span>
                    @elseif($isTrainer)
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill text-3xs fw-bold px-2 py-0.5">
                            <i class="fa-solid fa-id-card-clip me-1"></i>Trainer
                        </span>
                    @elseif($isStudent)
                        <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill text-3xs fw-bold px-2 py-0.5">
                            <i class="fa-solid fa-graduation-cap me-1"></i>Student
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="sidebar-nav-container custom-scrollbar px-3 py-2">
        <ul class="nav flex-column gap-1">

            {{-- ================= ADMIN NAVIGATION ================= --}}
            @if($isAdmin)
                <li class="nav-item-header text-uppercase text-white-50 fw-bold text-3xs px-3 pt-3 pb-1">Main</li>

                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fa-solid fa-gauge-high nav-icon"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>

                <li class="nav-item-header text-uppercase text-white-50 fw-bold text-3xs px-3 pt-3 pb-1">Management</li>

                <!-- Students -->
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.students.*') ? 'active' : '' }}" href="{{ Route::has('admin.students.index') ? route('admin.students.index') : '#' }}">
                        <i class="fa-solid fa-user-graduate nav-icon"></i>
                        <span class="nav-text">Students</span>
                    </a>
                </li>

                <!-- Trainers -->
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.trainers.*') ? 'active' : '' }}" href="{{ Route::has('admin.trainers.index') ? route('admin.trainers.index') : '#' }}">
                        <i class="fa-solid fa-chalkboard-user nav-icon"></i>
                        <span class="nav-text">Trainers</span>
                    </a>
                </li>

                <!-- Vehicles Fleet -->
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.vehicles.index') || Route::is('admin.vehicles.show') || Route::is('admin.vehicles.edit') || Route::is('admin.vehicles.create') ? 'active' : '' }}" href="{{ route('admin.vehicles.index') }}">
                        <i class="fa-solid fa-car-side nav-icon"></i>
                        <span class="nav-text">Vehicles Fleet</span>
                    </a>
                </li>

                <!-- Vehicle Timetable Matrix (V1 timetable) -->
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.vehicles.timetable') ? 'active' : '' }}" href="{{ route('admin.vehicles.timetable') }}">
                        <i class="fa-solid fa-table-cells nav-icon"></i>
                        <span class="nav-text">Fleet Timetable</span>
                    </a>
                </li>

                <!-- Fleet Maintenance -->
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.vehicle-services.*') ? 'active' : '' }}" href="{{ route('admin.vehicle-services.index') }}">
                        <i class="fa-solid fa-wrench nav-icon"></i>
                        <span class="nav-text">Fleet Maintenance</span>
                    </a>
                </li>

                <li class="nav-item-header text-uppercase text-white-50 fw-bold text-3xs px-3 pt-3 pb-1">Training Operations</li>

                <!-- Bookings / Schedule -->
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.bookings.*') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">
                        <i class="fa-solid fa-calendar-check nav-icon"></i>
                        <span class="nav-text">Lesson Bookings</span>
                    </a>
                </li>

                <!-- Attendance Logs -->
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.attendance.*') ? 'active' : '' }}" href="{{ route('admin.attendance.index') }}">
                        <i class="fa-solid fa-clipboard-user nav-icon"></i>
                        <span class="nav-text">Attendance Logs</span>
                    </a>
                </li>

                <!-- Student Progress -->
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.progress.*') ? 'active' : '' }}" href="{{ route('admin.progress.index') }}">
                        <i class="fa-solid fa-star-half-stroke nav-icon"></i>
                        <span class="nav-text">Student Progress</span>
                    </a>
                </li>

                <li class="nav-item-header text-uppercase text-white-50 fw-bold text-3xs px-3 pt-3 pb-1">Finance & Marketing</li>

                <!-- Payments -->
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.payments.*') ? 'active' : '' }}" href="{{ route('admin.payments.index') }}">
                        <i class="fa-solid fa-file-invoice-dollar nav-icon"></i>
                        <span class="nav-text">Payments Ledger</span>
                    </a>
                </li>

                <!-- Due Payments & Installments (V1 dueCustomers) -->
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.due-payments.*') ? 'active' : '' }}" href="{{ route('admin.due-payments.index') }}">
                        <i class="fa-solid fa-hand-holding-dollar nav-icon"></i>
                        <span class="nav-text">Due & Installments</span>
                    </a>
                </li>

                <!-- Email Broadcast & Offers (V1 offerMail) -->
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.broadcast.*') ? 'active' : '' }}" href="{{ route('admin.broadcast.index') }}">
                        <i class="fa-solid fa-bullhorn nav-icon"></i>
                        <span class="nav-text">Email Broadcast & Offers</span>
                    </a>
                </li>

                <li class="nav-item-header text-uppercase text-white-50 fw-bold text-3xs px-3 pt-3 pb-1">Testing & Certificates</li>

                <!-- Mock Tests -->
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.mock-tests.*') || Route::is('admin.mock-questions.*') ? 'active' : '' }}" href="{{ route('admin.mock-tests.index') }}">
                        <i class="fa-solid fa-list-check nav-icon"></i>
                        <span class="nav-text">Mock Tests Engine</span>
                    </a>
                </li>

                <!-- Certificates -->
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.certificates.*') ? 'active' : '' }}" href="{{ route('admin.certificates.index') }}">
                        <i class="fa-solid fa-award nav-icon"></i>
                        <span class="nav-text">Certificates</span>
                    </a>
                </li>

                <li class="nav-item-header text-uppercase text-white-50 fw-bold text-3xs px-3 pt-3 pb-1">System & Audit</li>

                <!-- Reports -->
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                        <i class="fa-solid fa-chart-pie nav-icon"></i>
                        <span class="nav-text">Reports & Analytics</span>
                    </a>
                </li>

                <!-- System Audit Logs (V1 viewLogs) -->
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.logs.*') ? 'active' : '' }}" href="{{ route('admin.logs.index') }}">
                        <i class="fa-solid fa-clipboard-list nav-icon"></i>
                        <span class="nav-text">System Audit Logs</span>
                    </a>
                </li>

                <!-- System Settings -->
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                        <i class="fa-solid fa-sliders nav-icon"></i>
                        <span class="nav-text">System Settings</span>
                    </a>
                </li>
            @endif

            {{-- ================= TRAINER NAVIGATION ================= --}}
            @if($isTrainer)
                <li class="nav-item-header text-uppercase text-white-50 fw-bold text-3xs px-3 pt-3 pb-1">Trainer Portal</li>

                <li class="nav-item">
                    <a class="nav-link {{ Route::is('trainer.dashboard') ? 'active' : '' }}" href="{{ route('trainer.dashboard') }}">
                        <i class="fa-solid fa-gauge-high nav-icon"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Route::is('trainer.bookings.*') ? 'active' : '' }}" href="{{ Route::has('trainer.bookings.index') ? route('trainer.bookings.index') : '#' }}">
                        <i class="fa-solid fa-calendar-day nav-icon"></i>
                        <span class="nav-text">My Schedule</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Route::is('trainer.attendance.*') ? 'active' : '' }}" href="{{ Route::has('trainer.attendance.index') ? route('trainer.attendance.index') : '#' }}">
                        <i class="fa-solid fa-clipboard-check nav-icon"></i>
                        <span class="nav-text">Mark Attendance</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Route::is('trainer.progress.*') ? 'active' : '' }}" href="{{ Route::has('trainer.progress.index') ? route('trainer.progress.index') : '#' }}">
                        <i class="fa-solid fa-star-half-stroke nav-icon"></i>
                        <span class="nav-text">Student Evaluations</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Route::is('trainer.students.*') ? 'active' : '' }}" href="{{ Route::has('trainer.students.index') ? route('trainer.students.index') : '#' }}">
                        <i class="fa-solid fa-users nav-icon"></i>
                        <span class="nav-text">My Students</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Route::is('trainer.vehicles.*') ? 'active' : '' }}" href="{{ Route::has('trainer.vehicles.index') ? route('trainer.vehicles.index') : '#' }}">
                        <i class="fa-solid fa-car nav-icon"></i>
                        <span class="nav-text">Assigned Vehicles</span>
                    </a>
                </li>
            @endif

            {{-- ================= STUDENT NAVIGATION ================= --}}
            @if($isStudent)
                <li class="nav-item-header text-uppercase text-white-50 fw-bold text-3xs px-3 pt-3 pb-1">Student Portal</li>

                <li class="nav-item">
                    <a class="nav-link {{ Route::is('student.dashboard') ? 'active' : '' }}" href="{{ route('student.dashboard') }}">
                        <i class="fa-solid fa-gauge-high nav-icon"></i>
                        <span class="nav-text">My Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Route::is('student.bookings.*') ? 'active' : '' }}" href="{{ Route::has('student.bookings.index') ? route('student.bookings.index') : '#' }}">
                        <i class="fa-solid fa-calendar-plus nav-icon"></i>
                        <span class="nav-text">Book Lessons</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Route::is('student.attendance.*') ? 'active' : '' }}" href="{{ Route::has('student.attendance.index') ? route('student.attendance.index') : '#' }}">
                        <i class="fa-solid fa-calendar-check nav-icon"></i>
                        <span class="nav-text">My Attendance</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Route::is('student.progress.*') ? 'active' : '' }}" href="{{ Route::has('student.progress.index') ? route('student.progress.index') : '#' }}">
                        <i class="fa-solid fa-chart-simple nav-icon"></i>
                        <span class="nav-text">Learning Progress</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Route::is('student.payments.*') ? 'active' : '' }}" href="{{ Route::has('student.payments.index') ? route('student.payments.index') : '#' }}">
                        <i class="fa-solid fa-receipt nav-icon"></i>
                        <span class="nav-text">Fee & Invoices</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Route::is('student.mock-tests.*') ? 'active' : '' }}" href="{{ Route::has('student.mock-tests.index') ? route('student.mock-tests.index') : '#' }}">
                        <i class="fa-solid fa-laptop-code nav-icon"></i>
                        <span class="nav-text">Theory Exam Center</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Route::is('student.certificate.*') ? 'active' : '' }}" href="{{ Route::has('student.certificate.index') ? route('student.certificate.index') : '#' }}">
                        <i class="fa-solid fa-certificate nav-icon"></i>
                        <span class="nav-text">My Certificate</span>
                    </a>
                </li>
            @endif

        </ul>
    </div>

    <!-- Sidebar Bottom Logout / System Info -->
    <div class="sidebar-footer p-3 border-top border-secondary-subtle">
        <form method="POST" action="{{ route('logout') }}" id="sidebarLogoutForm">
            @csrf
            <button type="submit" class="btn btn-outline-danger w-100 rounded-3 btn-sm d-flex align-items-center justify-content-center gap-2">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                <span class="fw-semibold">Sign Out</span>
            </button>
        </form>
    </div>
</aside>
