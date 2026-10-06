<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;

class ProgressController extends Controller
{
    public function index(Request $request)
    {
        $trainer = Auth::user()->trainer;
        if (!$trainer) {
            abort(403, 'Trainer profile not found.');
        }

        // Get bookings where we can record progress
        $bookings = Booking::with(['student.user', 'progress'])
            ->where('trainer_id', $trainer->id)
            ->where('status', 'completed')
            ->orderBy('booking_date', 'desc')
            ->paginate(10);

        return view('trainer.progress.index', compact('bookings'));
    }

    public function store(Request $request)
    {
        $trainer = Auth::user()->trainer;
        if (!$trainer) abort(403);

        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'skill_topic' => 'required|string|max:255',
            'score' => 'required|integer|min:1|max:5',
            'feedback' => 'required|string',
            'competency_status' => 'required|in:needs_practice,proficient,mastered'
        ]);

        $booking = Booking::where('id', $validated['booking_id'])
            ->where('trainer_id', $trainer->id)
            ->firstOrFail();

        Progress::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'student_id' => $booking->student_id,
                'trainer_id' => $trainer->id,
                'skill_topic' => $validated['skill_topic'],
                'score' => $validated['score'],
                'feedback' => $validated['feedback'],
                'competency_status' => $validated['competency_status'],
            ]
        );

        // Notify Student
        if ($booking->student && $booking->student->user) {
            NotificationService::send(
                $booking->student->user,
                'New Evaluation Received',
                'Your trainer submitted a progress evaluation for topic: ' . $validated['skill_topic'],
                'info',
                route('student.progress.index')
            );
        }

        return back()->with('success', 'Student progress evaluation saved!');
    }
}
