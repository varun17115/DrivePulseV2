<x-guest-layout>
    <div class="mb-4 text-center">
        <h5 class="fw-bold text-dark dark-text-light mb-1">Welcome Back</h5>
        <p class="text-muted fs-8 mb-0">Sign in to your DrivePulse account</p>
    </div>

    <!-- Quick Demo Accounts Switcher -->
    <div class="p-2.5 rounded-4 bg-light border mb-4">
        <span class="d-block text-muted text-3xs fw-bold text-uppercase mb-2 text-center">Quick Demo Login</span>
        <div class="d-grid gap-1 d-sm-flex justify-content-center">
            <button type="button" class="btn btn-sm btn-outline-danger flex-fill rounded-pill py-1 fs-8 demo-fill-btn" data-email="admin@drivepulse.com">
                <i class="fa-solid fa-shield-halved me-1"></i> Admin
            </button>
            <button type="button" class="btn btn-sm btn-outline-warning flex-fill rounded-pill py-1 fs-8 demo-fill-btn" data-email="marcus.trainer@drivepulse.com">
                <i class="fa-solid fa-user-tie me-1"></i> Trainer
            </button>
            <button type="button" class="btn btn-sm btn-outline-info flex-fill rounded-pill py-1 fs-8 demo-fill-btn" data-email="sophia.student@drivepulse.com">
                <i class="fa-solid fa-graduation-cap me-1"></i> Student
            </button>
        </div>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold fs-8">Email Address</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-end-0"><i class="fa-regular fa-envelope"></i></span>
                <input id="email" class="form-control border-start-0 @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="name@example.com">
            </div>
            @error('email')
                <div class="text-danger fs-8 mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="form-label fw-semibold fs-8 mb-0">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-decoration-none text-primary fs-8 fw-semibold" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-end-0"><i class="fa-solid fa-lock"></i></span>
                <input id="password" class="form-control border-start-0 @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
            </div>
            @error('password')
                <div class="text-danger fs-8 mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="form-check mb-4">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label for="remember_me" class="form-check-label text-muted fs-8">Remember my session</label>
        </div>

        <button type="submit" class="btn btn-primary w-100 rounded-3 py-2.5 fw-semibold shadow-sm mb-3">
            <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Sign In to Portal
        </button>

        @if (Route::has('register'))
            <div class="text-center text-muted fs-8">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-decoration-none text-primary fw-bold">Enroll as Student</a>
            </div>
        @endif
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.demo-fill-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.getElementById('email').value = this.dataset.email;
                    document.getElementById('password').value = 'password';
                });
            });
        });
    </script>
</x-guest-layout>
