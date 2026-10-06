<x-guest-layout>
    <div class="mb-4 text-center">
        <h5 class="fw-bold text-dark dark-text-light mb-1">Student Enrollment</h5>
        <p class="text-muted fs-8 mb-0">Create your new DrivePulse academy account</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label fw-semibold fs-8">Full Name</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-end-0"><i class="fa-regular fa-user"></i></span>
                <input id="name" class="form-control border-start-0 @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe">
            </div>
            @error('name')
                <div class="text-danger fs-8 mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold fs-8">Email Address</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-end-0"><i class="fa-regular fa-envelope"></i></span>
                <input id="email" class="form-control border-start-0 @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="john@example.com">
            </div>
            @error('email')
                <div class="text-danger fs-8 mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label fw-semibold fs-8">Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-end-0"><i class="fa-solid fa-lock"></i></span>
                <input id="password" class="form-control border-start-0 @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="new-password" placeholder="Minimum 8 characters">
            </div>
            @error('password')
                <div class="text-danger fs-8 mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-semibold fs-8">Confirm Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-end-0"><i class="fa-solid fa-lock"></i></span>
                <input id="password_confirmation" class="form-control border-start-0 @error('password_confirmation') is-invalid @enderror" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat your password">
            </div>
            @error('password_confirmation')
                <div class="text-danger fs-8 mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100 rounded-3 py-2.5 fw-semibold shadow-sm mb-3">
            <i class="fa-solid fa-user-plus me-1"></i> Complete Registration
        </button>

        <div class="text-center text-muted fs-8">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-decoration-none text-primary fw-bold">Sign In</a>
        </div>
    </form>
</x-guest-layout>
