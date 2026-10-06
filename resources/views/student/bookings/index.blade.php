@extends('layouts.admin')

@section('title', 'My Bookings')
@section('page_title', 'Lesson Schedule & Bookings')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">My Bookings</li>
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-calendar-alt text-primary me-2"></i>My Bookings history</h6>
        <a href="{{ route('student.bookings.create') }}" class="btn btn-sm btn-primary">Book New Lesson</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Date</th>
                        <th>Time</th>
                        <th>Trainer</th>
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
                            <td>{{ $booking->trainer->user->name }}</td>
                            <td>{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</td>
                            <td><span class="badge bg-light text-dark border">{{ ucfirst($booking->lesson_type) }}</span></td>
                            <td>
                                <span class="badge {{ $booking->status === 'completed' ? 'bg-success' : ($booking->status === 'cancelled' ? 'bg-danger' : 'bg-primary') }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                @if($booking->status === 'scheduled' && $booking->booking_date->isFuture())
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $booking->id }}">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>

                                    <!-- Cancellation Modal -->
                                    <div class="modal fade" id="cancelModal{{ $booking->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content text-start">
                                                <form action="{{ route('student.bookings.cancel', $booking->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Cancel Lesson</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Reason for cancellation</label>
                                                            <textarea name="cancellation_reason" class="form-control" rows="3" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
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
                            <td colspan="7" class="text-center py-5 text-muted">No bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
