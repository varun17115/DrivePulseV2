<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\NotificationService;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $trainer = Auth::user()->trainer;
        if (!$trainer) {
            abort(403, 'Trainer profile not found.');
        }

        // Show bookings for today and past that don't have attendance yet (or all completed ones)
        $query = Booking::with(['student.user', 'attendance'])
            ->where('trainer_id', $trainer->id)
            ->whereIn('status', ['scheduled', 'confirmed', 'completed', 'pending'])
            ->where('booking_date', '<=', Carbon::today())
            ->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'desc');

        $bookings = $query->paginate(15);
        return view('trainer.attendance.index', compact('bookings'));
    }

    public function store(Request $request)
    {
        $trainer = Auth::user()->trainer;
        if (!$trainer) abort(403);

        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'status' => 'required|in:present,absent,late',
            'trainer_remarks' => 'nullable|string'
        ]);

        $booking = Booking::where('id', $validated['booking_id'])
            ->where('trainer_id', $trainer->id)
            ->firstOrFail();

        // Prevent duplicate attendance insertion
        $attendance = Attendance::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'status' => $validated['status'],
                'trainer_remarks' => $validated['trainer_remarks'],
                'check_in_time' => $validated['status'] !== 'absent' ? now() : null,
            ]
        );

        // Auto-update booking to complete if present
        if ($validated['status'] === 'present' && in_array($booking->status, ['scheduled', 'confirmed', 'pending'])) {
            $booking->update(['status' => 'completed']);
        }

        // Notify Student
        if ($booking->student && $booking->student->user) {
            $statusLabel = ucfirst($validated['status']);
            $type = $validated['status'] === 'present' ? 'success' : ($validated['status'] === 'late' ? 'warning' : 'danger');
            NotificationService::send(
                $booking->student->user,
                'Attendance Marked: '.$statusLabel,
                'Your attendance for lesson on '.$booking->booking_date->format('d M, Y').' was recorded as '.$statusLabel.'.',
                $type,
                route('student.attendance.index')
            );
        }

        return back()->with('success', 'Attendance marked successfully.');
    }
}
