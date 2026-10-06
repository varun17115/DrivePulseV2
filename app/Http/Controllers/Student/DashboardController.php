<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Progress;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, 'Student profile not found.');
        }

        $stats = [
            'total_lessons' => $student->bookings()->count(),
            'completed_lessons' => $student->bookings()->where('status', 'completed')->count(),
            'due_payments' => $student->due_amount,
            'progress_percent' => 0,
        ];

        if ($stats['total_lessons'] > 0) {
            $stats['progress_percent'] = round(($stats['completed_lessons'] / $stats['total_lessons']) * 100);
        }

        $upcomingBooking = Booking::with(['trainer.user', 'vehicle'])
            ->where('student_id', $student->id)
            ->where('booking_date', '>=', Carbon::today())
            ->whereIn('status', ['scheduled', 'confirmed', 'pending'])
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->first();

        $recentEvaluations = Progress::with('trainer.user')
            ->where('student_id', $student->id)
            ->latest()
            ->take(5)
            ->get();

        $recentBookings = Booking::with(['trainer.user', 'vehicle'])
            ->where('student_id', $student->id)
            ->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->take(5)
            ->get();

        return view('student.dashboard', compact('student', 'stats', 'upcomingBooking', 'recentEvaluations', 'recentBookings'));
    }
}
