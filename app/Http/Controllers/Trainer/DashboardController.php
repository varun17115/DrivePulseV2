<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $trainer = $user->trainer;

        // Ensure user has a trainer profile
        if (!$trainer) {
            abort(403, 'Trainer profile not found.');
        }

        $today = Carbon::today();

        // Trainer Stats
        $stats = [
            'today_lessons' => Booking::where('trainer_id', $trainer->id)
                                ->where('booking_date', $today)
                                ->whereIn('status', ['scheduled', 'completed'])
                                ->count(),
            'upcoming_lessons' => Booking::where('trainer_id', $trainer->id)
                                ->where('booking_date', '>', $today)
                                ->where('status', 'scheduled')
                                ->count(),
            'total_students' => Booking::where('trainer_id', $trainer->id)
                                ->distinct('student_id')
                                ->count('student_id'),
            'hours_this_week' => Booking::where('trainer_id', $trainer->id)
                                ->whereBetween('booking_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                                ->where('status', 'completed')
                                ->count(), // Simple count of lessons, assuming 1hr each. Could calculate diffInHours.
        ];

        // Today's Schedule
        $todaySchedule = Booking::with(['student.user', 'vehicle'])
            ->where('trainer_id', $trainer->id)
            ->where('booking_date', $today)
            ->orderBy('start_time')
            ->get();

        return view('trainer.dashboard', compact('trainer', 'stats', 'todaySchedule'));
    }
}
