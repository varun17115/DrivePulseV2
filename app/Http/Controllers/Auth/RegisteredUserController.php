<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'active',
        ]);

        $user->assignRole('student');

        // Create Student profile record
        \App\Models\Student::create([
            'user_id' => $user->id,
            'admission_number' => 'STU-' . date('Y') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT),
            'enrollment_date' => now(),
            'course_status' => 'enrolled',
            'license_type' => 'Class C - General',
            'total_lessons_booked' => 0,
            'lessons_completed' => 0,
            'total_amount' => 500.00,
            'paid_amount' => 0.00,
            'due_amount' => 500.00,
        ]);

        // Send Welcome Notification
        \App\Services\NotificationService::send(
            $user,
            'Welcome to DrivePulse Academy!',
            'Your student account has been registered successfully. You can now book your driving lessons and start practice tests.',
            'success',
            route('student.dashboard')
        );

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
