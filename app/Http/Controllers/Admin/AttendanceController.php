<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $attendances = Attendance::with(['booking.student.user', 'booking.trainer.user'])
                ->select('attendance.*');

            return DataTables::of($attendances)
                ->addColumn('date_time', function ($attendance) {
                    if (!$attendance->booking) return 'N/A';
                    $date = $attendance->booking->booking_date->format('d M Y');
                    $time = Carbon::parse($attendance->booking->start_time)->format('h:i A');
                    return '<div class="fw-bold text-dark">' . $date . '</div><div class="fs-8 text-muted">' . $time . '</div>';
                })
                ->addColumn('student', function ($attendance) {
                    return $attendance->booking && $attendance->booking->student && $attendance->booking->student->user 
                        ? e($attendance->booking->student->user->name) 
                        : 'N/A';
                })
                ->addColumn('trainer', function ($attendance) {
                    return $attendance->booking && $attendance->booking->trainer && $attendance->booking->trainer->user 
                        ? e($attendance->booking->trainer->user->name) 
                        : 'N/A';
                })
                ->addColumn('status_badge', function ($attendance) {
                    $badges = [
                        'present' => 'bg-success',
                        'late' => 'bg-warning text-dark',
                        'absent' => 'bg-danger',
                    ];
                    $badgeClass = $badges[$attendance->status] ?? 'bg-secondary';
                    return '<span class="badge ' . $badgeClass . '">' . ucfirst($attendance->status) . '</span>';
                })
                ->addColumn('check_in', function ($attendance) {
                    return $attendance->check_in_time 
                        ? Carbon::parse($attendance->check_in_time)->format('h:i A') 
                        : '<span class="text-muted">-</span>';
                })
                ->addColumn('remarks', function ($attendance) {
                    return $attendance->trainer_remarks 
                        ? '<span class="text-truncate d-inline-block" style="max-width: 150px;" title="' . e($attendance->trainer_remarks) . '">' . e($attendance->trainer_remarks) . '</span>' 
                        : '<span class="text-muted">-</span>';
                })
                ->rawColumns(['date_time', 'status_badge', 'check_in', 'remarks'])
                ->make(true);
        }

        return view('admin.attendance.index');
    }
}
