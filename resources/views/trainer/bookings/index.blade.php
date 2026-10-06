@extends('layouts.admin')

@section('title', 'My Schedule')
@section('page_title', 'Trainer Schedule & Bookings')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">My Schedule</li>
@endsection

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-calendar-alt text-primary me-2"></i>My Booked Lessons</h6>

        <form method="GET" action="{{ route('trainer.bookings.index') }}" class="d-flex gap-2">
            <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
            @if(request('date'))
                <a href="{{ route('trainer.bookings.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            @endif
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Date</th>
                        <th>Time</th>
                        <th>Student</th>
                        <th>Vehicle</th>
                        <th>Lesson Type</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $booking->booking_date->format('d M, Y') }}</td>
                            <td class="text-primary font-monospace">
                                {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                        {{ substr($booking->student->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $booking->student->user->name }}</div>
                                        <div class="small text-muted">{{ $booking->student->user->phone ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($booking->vehicle)
                                    <div>{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</div>
                                    <div class="small text-muted">{{ $booking->vehicle->registration_number }}</div>
                                @else
                                    <span class="text-muted">Not Assigned</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ ucfirst($booking->lesson_type) }}</span>
                            </td>
                            <td>
                                @if($booking->status === 'completed')
                                    <span class="badge bg-success px-2 py-1">Completed</span>
                                @elseif($booking->status === 'scheduled')
                                    <span class="badge bg-primary px-2 py-1">Scheduled</span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="badge bg-danger px-2 py-1">Cancelled</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                @if($booking->status === 'scheduled')
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Status
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <form action="{{ route('trainer.bookings.status.update', $booking->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="completed">
                                                    <button type="submit" class="dropdown-item text-success"><i class="fa-solid fa-check me-2"></i>Mark Completed</button>
                                                </form>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $booking->id }}">
                                                    <i class="fa-solid fa-xmark me-2"></i>Cancel Lesson
                                                </button>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Cancel Modal -->
                                    <div class="modal fade" id="cancelModal{{ $booking->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content text-start">
                                                <form action="{{ route('trainer.bookings.status.update', $booking->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Cancel Lesson</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Reason for Cancellation</label>
                                                            <textarea name="cancellation_reason" class="form-control" rows="3" required placeholder="Weather, emergency, student absent, etc."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-danger">Confirm Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-calendar-xmark fa-3x mb-3 text-secondary"></i>
                                <p class="mb-0">No bookings found for the selected schedule.</p>
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
