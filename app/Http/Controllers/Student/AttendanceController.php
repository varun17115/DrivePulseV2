<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;
        if (!$student) abort(403);

        $attendances = Attendance::whereHas('booking', function ($q) use ($student) {
                $q->where('student_id', $student->id);
            })
            ->with(['booking.trainer.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('student.attendance.index', compact('attendances'));
    }
}
