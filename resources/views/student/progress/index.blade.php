@extends('layouts.admin')

@section('title', 'Driving Competency Progress')
@section('page_title', 'My Driving Progress & Feedback')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Progress</li>
@endsection

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="small text-muted mb-1">Average Performance Score</div>
            <div class="display-6 fw-bold text-primary">{{ number_format($avgScore, 1) }}<span class="fs-6 text-muted">/5</span></div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent py-3">
        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-chart-line text-primary me-2"></i>Evaluations from Instructors</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Date</th>
                        <th>Trainer</th>
                        <th>Skill / Topic</th>
                        <th>Competency</th>
                        <th>Score</th>
                        <th>Feedback</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($evaluations as $eval)
                        <tr>
                            <td class="ps-4">{{ $eval->created_at->format('d M, Y') }}</td>
                            <td>{{ $eval->trainer->user->name }}</td>
                            <td>{{ $eval->skill_topic }}</td>
                            <td>
                                @php
                                    $badges = [
                                        'needs_practice' => 'bg-warning text-dark',
                                        'proficient' => 'bg-info text-dark',
                                        'mastered' => 'bg-success'
                                    ];
                                @endphp
                                <span class="badge {{ $badges[$eval->competency_status] ?? 'bg-secondary' }}">
                                    {{ ucwords(str_replace('_', ' ', $eval->competency_status)) }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $eval->score }}/5</span>
                            </td>
                            <td>{{ $eval->feedback }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No progress evaluations logged yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
