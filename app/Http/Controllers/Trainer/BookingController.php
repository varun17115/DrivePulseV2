<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $trainer = Auth::user()->trainer;
        if (!$trainer) {
            abort(403, 'Trainer profile not found.');
        }

        $query = Booking::with(['student.user', 'vehicle'])
            ->where('trainer_id', $trainer->id);

        // Optional date filter
        if ($request->has('date') && $request->date) {
            $query->where('booking_date', $request->date);
        } else {
            // Default to future/today bookings, ordered chronologically
            $query->where('booking_date', '>=', Carbon::today());
        }

        $bookings = $query->orderBy('booking_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('trainer.bookings.index', compact('bookings'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $trainer = Auth::user()->trainer;
        if (!$trainer || $booking->trainer_id !== $trainer->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:scheduled,completed,cancelled',
            'cancellation_reason' => 'required_if:status,cancelled|nullable|string'
        ]);

        $booking->update([
            'status' => $validated['status'],
            'cancellation_reason' => $validated['status'] === 'cancelled' ? $validated['cancellation_reason'] : null
        ]);

        return back()->with('success', 'Booking status updated successfully.');
    }
}
