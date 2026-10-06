<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Trainer;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\NotificationService;

class BookingController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;
        if (!$student) abort(403);

        $bookings = Booking::with(['trainer.user', 'vehicle'])
            ->where('student_id', $student->id)
            ->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(15);

        return view('student.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $trainers = Trainer::with('user')->whereIn('status', ['available', 'active'])->get();
        $vehicles = Vehicle::where('status', 'active')->get();
        return view('student.bookings.create', compact('trainers', 'vehicles'));
    }

    public function store(Request $request)
    {
        $student = Auth::user()->student;
        if (!$student) abort(403);

        $validated = $request->validate([
            'trainer_id' => 'required|exists:trainers,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'lesson_type' => 'required|in:theory,practical,refresher',
        ]);

        $booking = Booking::create([
            'student_id' => $student->id,
            'trainer_id' => $validated['trainer_id'],
            'vehicle_id' => $validated['vehicle_id'],
            'booking_date' => $validated['booking_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'lesson_type' => $validated['lesson_type'],
            'status' => 'scheduled',
        ]);

        // Send Notification to Student
        NotificationService::send(
            Auth::user(),
            'Lesson Booked',
            'You booked a '.$validated['lesson_type'].' lesson on '.$validated['booking_date'].' at '.$validated['start_time'].'.',
            'success',
            route('student.bookings.index')
        );

        // Send Notification to Trainer
        $trainer = Trainer::find($validated['trainer_id']);
        if ($trainer && $trainer->user) {
            NotificationService::send(
                $trainer->user,
                'New Lesson Appointment',
                $student->user->name.' booked a '.$validated['lesson_type'].' session on '.$validated['booking_date'].' at '.$validated['start_time'].'.',
                'info',
                route('trainer.appointments.index')
            );
        }

        return redirect()->route('student.bookings.index')->with('success', 'Lesson booked successfully!');
    }

    public function cancel(Request $request, Booking $booking)
    {
        $student = Auth::user()->student;
        if (!$student || $booking->student_id !== $student->id) abort(403);

        $validated = $request->validate([
            'cancellation_reason' => 'required|string|max:255',
        ]);

        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $validated['cancellation_reason']
        ]);

        // Notify Trainer
        if ($booking->trainer && $booking->trainer->user) {
            NotificationService::send(
                $booking->trainer->user,
                'Lesson Cancelled',
                $student->user->name.' cancelled the lesson scheduled for '.$booking->booking_date->format('Y-m-d').'. Reason: '.$validated['cancellation_reason'],
                'warning',
                route('trainer.appointments.index')
            );
        }

        return back()->with('success', 'Booking cancelled successfully.');
    }
}
