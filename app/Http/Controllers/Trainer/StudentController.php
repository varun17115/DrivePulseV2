<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Booking;
use App\Models\Progress;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $trainer = Auth::user()->trainer;
        if (!$trainer) abort(403, 'Trainer profile not found.');

        // Fetch students who have had lessons with this trainer
        $studentIds = Booking::where('trainer_id', $trainer->id)
            ->pluck('student_id')
            ->unique();

        $students = Student::with(['user', 'bookings' => function ($q) use ($trainer) {
                $q->where('trainer_id', $trainer->id);
            }])
            ->whereIn('id', $studentIds)
            ->paginate(12);

        return view('trainer.students.index', compact('students'));
    }

    public function show(Student $student)
    {
        $trainer = Auth::user()->trainer;
        if (!$trainer) abort(403, 'Trainer profile not found.');

        // Verify this student is associated with trainer
        $hasBooking = Booking::where('trainer_id', $trainer->id)
            ->where('student_id', $student->id)
            ->exists();

        if (!$hasBooking) {
            abort(403, 'You do not have permission to view this student profile.');
        }

        $student->load(['user']);

        $bookings = Booking::with(['vehicle', 'attendance', 'progress'])
            ->where('trainer_id', $trainer->id)
            ->where('student_id', $student->id)
            ->orderBy('booking_date', 'desc')
            ->get();

        $evaluations = Progress::where('trainer_id', $trainer->id)
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $avgScore = $evaluations->avg('score') ?? 0;

        return view('trainer.students.show', compact('student', 'bookings', 'evaluations', 'avgScore'));
    }
}
