@extends('layouts.admin')

@section('title', 'Student Driving Evaluations')
@section('page_title', 'Driving Competency & Progress Evaluations')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Student Progress</li>
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent py-3">
        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-chart-line text-primary me-2"></i>Completed Lesson Evaluations</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Date</th>
                        <th>Student</th>
                        <th>Skill / Topic</th>
                        <th>Competency Status</th>
                        <th>Score (1-5)</th>
                        <th>Feedback</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-semibold">{{ $booking->booking_date->format('d M, Y') }}</div>
                                <div class="small text-muted font-monospace">{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $booking->student->user->name }}</div>
                                <div class="small text-muted">{{ $booking->student->user->email }}</div>
                            </td>
                            <td>
                                @if($booking->progress)
                                    <span class="fw-semibold">{{ $booking->progress->skill_topic }}</span>
                                @else
                                    <span class="text-muted fst-italic">Pending evaluation</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->progress)
                                    @php
                                        $badges = [
                                            'needs_practice' => 'bg-warning text-dark',
                                            'proficient' => 'bg-info text-dark',
                                            'mastered' => 'bg-success'
                                        ];
                                    @endphp
                                    <span class="badge {{ $badges[$booking->progress->competency_status] ?? 'bg-secondary' }}">
                                        {{ ucwords(str_replace('_', ' ', $booking->progress->competency_status)) }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">Not Evaluated</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->progress)
                                    <div class="d-flex align-items-center">
                                        <span class="fw-bold me-2">{{ $booking->progress->score }}/5</span>
                                        <div class="progress flex-grow-1" style="height: 6px; width: 60px;">
                                            <div class="progress-bar {{ $booking->progress->score >= 4 ? 'bg-success' : ($booking->progress->score >= 3 ? 'bg-warning' : 'bg-danger') }}"
                                                 style="width: {{ ($booking->progress->score / 5) * 100 }}%"></div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted small text-truncate d-inline-block" style="max-width: 180px;" title="{{ $booking->progress->feedback ?? '' }}">
                                    {{ $booking->progress->feedback ?? '-' }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#evalModal{{ $booking->id }}">
                                    <i class="fa-solid fa-star me-1"></i> {{ $booking->progress ? 'Edit' : 'Evaluate' }}
                                </button>

                                <!-- Evaluation Modal -->
                                <div class="modal fade" id="evalModal{{ $booking->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content text-start">
                                            <form action="{{ route('trainer.progress.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Evaluate {{ $booking->student->user->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Skill / Topic Covered</label>
                                                        <input type="text" name="skill_topic" class="form-control" placeholder="e.g. Parallel Parking, Roundabouts, Highway driving" value="{{ $booking->progress->skill_topic ?? '' }}" required>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label fw-semibold">Score (1 to 5)</label>
                                                            <select name="score" class="form-select" required>
                                                                <option value="1" {{ ($booking->progress->score ?? 3) == 1 ? 'selected' : '' }}>1 - Poor</option>
                                                                <option value="2" {{ ($booking->progress->score ?? 3) == 2 ? 'selected' : '' }}>2 - Fair</option>
                                                                <option value="3" {{ ($booking->progress->score ?? 3) == 3 ? 'selected' : '' }}>3 - Satisfactory</option>
                                                                <option value="4" {{ ($booking->progress->score ?? 3) == 4 ? 'selected' : '' }}>4 - Good</option>
                                                                <option value="5" {{ ($booking->progress->score ?? 3) == 5 ? 'selected' : '' }}>5 - Excellent</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label fw-semibold">Competency Level</label>
                                                            <select name="competency_status" class="form-select" required>
                                                                <option value="needs_practice" {{ ($booking->progress->competency_status ?? '') === 'needs_practice' ? 'selected' : '' }}>Needs Practice</option>
                                                                <option value="proficient" {{ ($booking->progress->competency_status ?? '') === 'proficient' ? 'selected' : '' }}>Proficient</option>
                                                                <option value="mastered" {{ ($booking->progress->competency_status ?? '') === 'mastered' ? 'selected' : '' }}>Mastered</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Detailed Feedback & Notes</label>
                                                        <textarea name="feedback" class="form-control" rows="3" required placeholder="Clutch control, mirror checks, confidence level...">{{ $booking->progress->feedback ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Save Evaluation</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-graduation-cap fa-3x mb-3 text-secondary"></i>
                                <p class="mb-0">No completed lessons available to evaluate yet.</p>
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
