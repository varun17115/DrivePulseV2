@extends('layouts.admin')

@section('title', 'My Attendance')
@section('page_title', 'Attendance History')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Attendance</li>
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent py-3">
        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-clipboard-user text-primary me-2"></i>My Attendance Logs</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Date & Time</th>
                        <th>Trainer</th>
                        <th>Status</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $att)
                        <tr>
                            <td class="ps-4">
                                {{ $att->booking->booking_date->format('d M, Y') }}
                                <div class="small text-muted font-monospace">
                                    {{ \Carbon\Carbon::parse($att->booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($att->booking->end_time)->format('h:i A') }}
                                </div>
                            </td>
                            <td>{{ $att->booking->trainer->user->name }}</td>
                            <td>
                                <span class="badge {{ $att->status === 'present' ? 'bg-success' : ($att->status === 'late' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                    {{ ucfirst($att->status) }}
                                </span>
                            </td>
                            <td>{{ $att->trainer_remarks ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">No attendance logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($attendances->hasPages())
        <div class="card-footer bg-transparent py-3">
            {{ $attendances->links() }}
        </div>
    @endif
</div>
@endsection
