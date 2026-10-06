<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use App\Models\Student;
use App\Models\Trainer;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $bookings = Booking::with(['student.user', 'trainer.user', 'vehicle'])->select('bookings.*');

            return DataTables::of($bookings)
                ->addColumn('date_time', function ($booking) {
                    $date = $booking->booking_date->format('d M Y');
                    $time = Carbon::parse($booking->start_time)->format('h:i A') . ' - ' . Carbon::parse($booking->end_time)->format('h:i A');
                    return '<div class="fw-bold text-dark">' . $date . '</div><div class="fs-8 text-muted">' . $time . '</div>';
                })
                ->addColumn('student', function ($booking) {
                    return $booking->student && $booking->student->user ? e($booking->student->user->name) : 'N/A';
                })
                ->addColumn('trainer', function ($booking) {
                    return $booking->trainer && $booking->trainer->user ? e($booking->trainer->user->name) : 'N/A';
                })
                ->addColumn('lesson_badge', function ($booking) {
                    $badges = [
                        'theory' => 'bg-info',
                        'practical' => 'bg-primary',
                        'simulator' => 'bg-secondary',
                        'mock_test' => 'bg-warning text-dark',
                    ];
                    $badgeClass = $badges[$booking->lesson_type] ?? 'bg-dark';
                    return '<span class="badge ' . $badgeClass . '">' . ucwords(str_replace('_', ' ', $booking->lesson_type)) . '</span>';
                })
                ->addColumn('status_badge', function ($booking) {
                    $badges = [
                        'pending' => 'bg-warning',
                        'confirmed' => 'bg-info',
                        'completed' => 'bg-success',
                        'cancelled' => 'bg-danger',
                        'no_show' => 'bg-secondary',
                    ];
                    $badgeClass = $badges[$booking->status] ?? 'bg-dark';
                    return '<span class="badge ' . $badgeClass . '">' . ucfirst(str_replace('_', ' ', $booking->status)) . '</span>';
                })
                ->addColumn('actions', function ($booking) {
                    $showUrl = route('admin.bookings.show', $booking->id);
                    $editUrl = route('admin.bookings.edit', $booking->id);
                    $deleteUrl = route('admin.bookings.destroy', $booking->id);

                    return '
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="' . $showUrl . '" class="btn btn-sm btn-outline-info" title="View"><i class="fa-solid fa-eye"></i></a>
                            <a href="' . $editUrl . '" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-url="' . $deleteUrl . '" data-name="Booking on ' . $booking->booking_date->format('d M') . '" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['date_time', 'lesson_badge', 'status_badge', 'actions'])
                ->make(true);
        }

        return view('admin.bookings.index');
    }

    public function calendar(Request $request)
    {
        if ($request->ajax()) {
            $bookings = Booking::with(['student.user', 'trainer.user', 'vehicle'])->get();
            $events = $bookings->map(function ($booking) {
                $color = match($booking->status) {
                    'pending' => '#ffc107',
                    'confirmed' => '#0dcaf0',
                    'completed' => '#198754',
                    'cancelled' => '#dc3545',
                    'no_show' => '#6c757d',
                    default => '#0d6efd'
                };

                return [
                    'id' => $booking->id,
                    'title' => ($booking->student->user->name ?? 'Student') . ' (' . ucfirst($booking->lesson_type) . ')',
                    'start' => $booking->booking_date->format('Y-m-d') . 'T' . $booking->start_time,
                    'end' => $booking->booking_date->format('Y-m-d') . 'T' . $booking->end_time,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'extendedProps' => [
                        'trainer' => $booking->trainer->user->name ?? 'Trainer',
                        'status' => ucfirst($booking->status),
                        'url' => route('admin.bookings.show', $booking->id)
                    ]
                ];
            });

            return response()->json($events);
        }

        return view('admin.bookings.calendar');
    }

    public function create()
    {
        $students = Student::with('user')->whereHas('user', function($q) { $q->where('status', 'active'); })->get();
        $trainers = Trainer::with('user')->where('status', 'available')->get();
        $vehicles = Vehicle::where('status', 'active')->get();

        return view('admin.bookings.create', compact('students', 'trainers', 'vehicles'));
    }

    public function store(StoreBookingRequest $request)
    {
        // Check for conflicts
        $conflict = $this->checkConflicts(
            $request->booking_date,
            $request->start_time,
            $request->end_time,
            $request->trainer_id,
            $request->vehicle_id,
            $request->student_id
        );

        if ($conflict) {
            return back()->withInput()->with('error', $conflict);
        }

        try {
            Booking::create($request->validated());
            return redirect()->route('admin.bookings.index')->with('success', 'Booking scheduled successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to schedule booking: ' . $e->getMessage());
        }
    }

    public function show(Booking $booking)
    {
        $booking->load(['student.user', 'trainer.user', 'vehicle', 'attendance', 'progress']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function recordAttendance(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:present,absent,late',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
            'trainer_remarks' => 'nullable|string|max:500',
        ]);

        $booking->attendance()->updateOrCreate(
            ['booking_id' => $booking->id],
            $validated
        );

        return redirect()->route('admin.bookings.show', $booking->id)->with('success', 'Attendance logged successfully!');
    }

    public function recordProgress(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'skill_topic' => 'required|string|max:150',
            'score' => 'required|integer|min:1|max:5',
            'competency_status' => 'required|in:needs_practice,proficient,mastered',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $booking->progress()->create([
            'student_id' => $booking->student_id,
            'trainer_id' => $booking->trainer_id,
            'skill_topic' => $validated['skill_topic'],
            'score' => $validated['score'],
            'competency_status' => $validated['competency_status'],
            'feedback' => $validated['feedback'],
        ]);

        return redirect()->route('admin.bookings.show', $booking->id)->with('success', 'Progress evaluation saved successfully!');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled,no_show',
            'cancellation_reason' => 'nullable|string|required_if:status,cancelled',
        ]);

        $booking->update($validated);

        return back()->with('success', 'Booking status updated successfully!');
    }

    public function edit(Booking $booking)
    {
        $students = Student::with('user')->get();
        $trainers = Trainer::with('user')->get();
        $vehicles = Vehicle::all();

        return view('admin.bookings.edit', compact('booking', 'students', 'trainers', 'vehicles'));
    }

    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        // Check for conflicts (excluding current booking)
        $conflict = $this->checkConflicts(
            $request->booking_date,
            $request->start_time,
            $request->end_time,
            $request->trainer_id,
            $request->vehicle_id,
            $request->student_id,
            $booking->id
        );

        if ($conflict) {
            return back()->withInput()->with('error', $conflict);
        }

        try {
            $booking->update($request->validated());
            return redirect()->route('admin.bookings.index')->with('success', 'Booking updated successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update booking: ' . $e->getMessage());
        }
    }

    public function destroy(Booking $booking)
    {
        try {
            $booking->delete();

            if (request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'Booking deleted successfully!']);
            }

            return redirect()->route('admin.bookings.index')->with('success', 'Booking deleted successfully!');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete booking: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Failed to delete booking: ' . $e->getMessage());
        }
    }

    private function checkConflicts($date, $start, $end, $trainer_id, $vehicle_id, $student_id, $exclude_id = null)
    {
        $query = Booking::where('booking_date', $date)
            ->where('status', '!=', 'cancelled')
            ->where(function ($q) use ($start, $end) {
                // Check if time blocks overlap
                $q->where(function($q2) use ($start, $end) {
                    $q2->where('start_time', '<', $end)->where('end_time', '>', $start);
                });
            });

        if ($exclude_id) {
            $query->where('id', '!=', $exclude_id);
        }

        // Clone query for each entity check
        $trainerConflict = (clone $query)->where('trainer_id', $trainer_id)->first();
        if ($trainerConflict) {
            return 'Trainer is already booked during this time slot.';
        }

        $vehicleConflict = (clone $query)->where('vehicle_id', $vehicle_id)->first();
        if ($vehicleConflict) {
            return 'Vehicle is already booked during this time slot.';
        }

        $studentConflict = (clone $query)->where('student_id', $student_id)->first();
        if ($studentConflict) {
            return 'Student already has a booking during this time slot.';
        }

        return false;
    }
}
