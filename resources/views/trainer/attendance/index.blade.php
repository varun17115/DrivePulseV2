@extends('layouts.admin')

@section('title', 'Mark Attendance')
@section('page_title', 'Student Attendance Logger')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Mark Attendance</li>
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent py-3">
        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-clipboard-check text-primary me-2"></i>Lessons Attendance Log</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Date & Time</th>
                        <th>Student</th>
                        <th>Lesson Type</th>
                        <th>Current Attendance</th>
                        <th>Remarks</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-semibold">{{ $booking->booking_date->format('d M, Y') }}</div>
                                <div class="small text-muted font-monospace">
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $booking->student->user->name }}</div>
                                <div class="small text-muted">{{ $booking->student->user->phone }}</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ ucfirst($booking->lesson_type) }}</span>
                            </td>
                            <td>
                                @if($booking->attendance)
                                    @if($booking->attendance->status === 'present')
                                        <span class="badge bg-success"><i class="fa-solid fa-check me-1"></i> Present</span>
                                    @elseif($booking->attendance->status === 'absent')
                                        <span class="badge bg-danger"><i class="fa-solid fa-xmark me-1"></i> Absent</span>
                                    @elseif($booking->attendance->status === 'late')
                                        <span class="badge bg-warning text-dark"><i class="fa-solid fa-clock me-1"></i> Late</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">Not Marked</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted small">{{ $booking->attendance->trainer_remarks ?? '-' }}</span>
                            </td>
                            <td class="text-end pe-4">
                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#attModal{{ $booking->id }}">
                                    <i class="fa-solid fa-pen-to-square me-1"></i> {{ $booking->attendance ? 'Update' : 'Mark' }}
                                </button>

                                <!-- Attendance Modal -->
                                <div class="modal fade" id="attModal{{ $booking->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content text-start">
                                            <form action="{{ route('trainer.attendance.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Record Attendance for {{ $booking->student->user->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Attendance Status</label>
                                                        <select name="status" class="form-select" required>
                                                            <option value="present" {{ ($booking->attendance->status ?? '') === 'present' ? 'selected' : '' }}>Present</option>
                                                            <option value="late" {{ ($booking->attendance->status ?? '') === 'late' ? 'selected' : '' }}>Late</option>
                                                            <option value="absent" {{ ($booking->attendance->status ?? '') === 'absent' ? 'selected' : '' }}>Absent</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Trainer Remarks (Optional)</label>
                                                        <textarea name="trainer_remarks" class="form-control" rows="3" placeholder="Punctuality, conduct, readiness...">{{ $booking->attendance->trainer_remarks ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Save Attendance</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-clipboard-question fa-3x mb-3 text-secondary"></i>
                                <p class="mb-0">No past or current lessons ready for attendance marking.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($bookings->hasPages())
        <div class="card-footer bg-transparent py-3">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection
