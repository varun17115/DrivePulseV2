@php
    $user = auth()->user();
    $isAdmin = $user->hasRole('admin');
    $isTrainer = $user->hasRole('trainer');
    $isStudent = $user->hasRole('student');
@endphp

<header class="top-navbar sticky-top bg-white border-bottom border-secondary-subtle px-4 py-2.5">
    <div class="d-flex align-items-center justify-content-between">

        <!-- Left: Sidebar Toggle & Page Title / Breadcrumb -->
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-icon btn-light rounded-3 text-secondary" id="sidebarToggleBtn" title="Toggle Sidebar">
                <i class="fa-solid fa-bars-staggered fs-5"></i>
            </button>

            <div class="d-none d-md-block">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 text-xs">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">
                                <i class="fa-solid fa-house-chimney me-1"></i>DrivePulse
                            </a>
                        </li>
                        @yield('breadcrumbs')
                    </ol>
                </nav>
                <h5 class="page-title text-dark fw-bold mb-0">@yield('page_title', 'Dashboard')</h5>
            </div>
        </div>

        <!-- Right: Actions, Notifications & Profile -->
        <div class="d-flex align-items-center gap-2 gap-md-3">

            <!-- Quick Date / Clock Widget -->
            <div class="d-none d-xl-flex align-items-center gap-2 bg-light px-3 py-1.5 rounded-pill border border-secondary-subtle text-muted text-xs">
                <i class="fa-regular fa-calendar-days text-primary"></i>
                <span class="fw-medium" id="currentDateDisplay">{{ now()->format('l, d M Y') }}</span>
            </div>

            <!-- Theme Toggle Button (Dark / Light Mode) -->
            <button class="btn btn-icon btn-light rounded-3 text-secondary theme-toggle-btn" id="themeToggleBtn" type="button" title="Switch Theme">
                <i class="fa-solid fa-moon fs-5 text-primary theme-icon-moon"></i>
                <i class="fa-solid fa-sun fs-5 text-warning theme-icon-sun d-none"></i>
            </button>

            <!-- Notifications Dropdown -->
            @php
                $userNotifications = $user->notifications()->latest()->take(5)->get();
                $unreadCount = $user->notifications()->where('is_read', false)->count();
            @endphp
            <div class="dropdown">
                <button class="btn btn-icon btn-light rounded-3 position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Notifications">
                    <i class="fa-regular fa-bell fs-5 text-secondary"></i>
                    @if($unreadCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle p-1.5 bg-danger border border-light rounded-circle notification-pulse">
                            <span class="visually-hidden">New alerts</span>
                        </span>
                    @endif
                </button>
                <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-0 dropdown-menu-notification" style="width: 340px;">
                    <div class="p-3 border-bottom d-flex align-items-center justify-content-between bg-light rounded-top-4">
                        <h6 class="mb-0 fw-bold text-dark fs-7">
                            <i class="fa-solid fa-bell text-primary me-2"></i>Notifications
                        </h6>
                        @if($unreadCount > 0)
                            <span class="badge bg-primary-subtle text-primary rounded-pill text-3xs">{{ $unreadCount }} New</span>
                        @endif
                    </div>
                    <div class="notification-list custom-scrollbar p-2" style="max-height: 280px; overflow-y: auto;">
                        @forelse($userNotifications as $item)
                            <a href="{{ $item->action_url ?? route('notifications.index') }}" class="dropdown-item d-flex gap-3 p-2.5 rounded-3 mb-1 text-wrap {{ $item->is_read ? '' : 'bg-light' }}">
                                <div class="icon-circle bg-{{ $item->type }}-subtle text-{{ $item->type }} flex-shrink-0">
                                    @if($item->type === 'success')
                                        <i class="fa-solid fa-circle-check"></i>
                                    @elseif($item->type === 'warning')
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                    @elseif($item->type === 'danger')
                                        <i class="fa-solid fa-circle-xmark"></i>
                                    @else
                                        <i class="fa-solid fa-info"></i>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-0 fs-8 fw-semibold text-dark">{{ $item->title }}</p>
                                    <p class="mb-0 text-muted text-3xs">{{ Str::limit($item->message, 60) }}</p>
                                    <span class="text-3xs text-muted">{{ $item->created_at->diffForHumans() }}</span>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-4 text-muted">
                                <i class="fa-regular fa-bell-slash fs-4 mb-2 text-secondary d-block"></i>
                                <span class="fs-8">No notifications</span>
                            </div>
                        @endforelse
                    </div>
                    <div class="p-2 border-top text-center bg-light rounded-bottom-4">
                        <a href="{{ route('notifications.index') }}" class="text-decoration-none text-primary fw-semibold fs-8">View All Notifications</a>
                    </div>
                </div>
            </div>

            <!-- User Profile Dropdown -->
            <div class="dropdown">
                <button class="btn p-0 border-0 d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=4f46e5&color=fff&bold=true' }}"
                         alt="{{ $user->name }}"
                         class="rounded-circle avatar-img border border-2 border-primary-subtle" width="38" height="38">
                    <div class="d-none d-lg-block text-start">
                        <span class="d-block fw-bold text-dark fs-8 lh-1">{{ $user->name }}</span>
                        <span class="d-block text-muted text-3xs text-capitalize">
                            @if($isAdmin) Administrator @elseif($isTrainer) Trainer @else Student @endif
                        </span>
                    </div>
                    <i class="fa-solid fa-chevron-down text-muted fs-8 d-none d-lg-inline"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-2 mt-2 dropdown-menu-profile" style="min-width: 230px;">
                    <li class="px-3 py-2 border-bottom mb-2 bg-light rounded-3">
                        <p class="mb-0 fw-bold text-dark fs-7">{{ $user->name }}</p>
                        <p class="mb-0 text-muted text-3xs text-truncate">{{ $user->email }}</p>
                        <div class="mt-1">
                            @if($isAdmin)
                                <span class="badge bg-danger-subtle text-danger rounded-pill text-3xs fw-bold">Admin Portal</span>
                            @elseif($isTrainer)
                                <span class="badge bg-warning-subtle text-warning rounded-pill text-3xs fw-bold">Trainer Portal</span>
                            @else
                                <span class="badge bg-info-subtle text-info rounded-pill text-3xs fw-bold">Student Portal</span>
                            @endif
                        </div>
                    </li>
                    <li>
                        <a class="dropdown-item rounded-3 py-2 fs-8" href="{{ route('profile.edit') }}">
                            <i class="fa-regular fa-user text-primary me-2"></i>My Profile Settings
                        </a>
                    </li>
                    @if($isAdmin)
                    <li>
                        <a class="dropdown-item rounded-3 py-2 fs-8" href="{{ Route::has('admin.settings.index') ? route('admin.settings.index') : '#' }}">
                            <i class="fa-solid fa-sliders text-secondary me-2"></i>System Settings
                        </a>
                    </li>
                    @endif
                    <li><hr class="dropdown-divider my-2"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item rounded-3 py-2 fs-8 text-danger">
                                <i class="fa-solid fa-arrow-right-from-bracket me-2"></i>Sign Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

        </div>

    </div>
</header>
