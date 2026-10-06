@extends('layouts.admin')

@section('title', 'Driving Theory Exam Center')
@section('page_title', 'Driving Theory & Road Safety Exam Center')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Mock Exam Center</li>
@endsection

@push('styles')
<style>
    .exam-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        border: 1px solid rgba(0,0,0,0.06);
    }
    .exam-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.08) !important;
    }
    .badge-icon-box {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .stat-hero-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: #fff;
        border-radius: 16px;
    }
    .progress-radial-text {
        font-family: monospace;
    }
</style>
@endpush

@section('content')
<!-- Hero Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card stat-hero-card border-0 shadow-sm p-4">
            <div class="card-body p-2 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-primary px-3 py-1 rounded-pill text-uppercase fw-semibold tracking-wider">Official Simulation Mode</span>
                        <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-semibold"><i class="fa-solid fa-certificate me-1"></i> Pass Criteria: 80%</span>
                    </div>
                    <h2 class="fw-bold text-white mb-2">Driving License Theory & Hazard Test Practice</h2>
                    <p class="text-white-50 mb-0 max-w-2xl fs-6">Prepare for your official driving license exam with our real-time computer-based test simulator. Practice full exams, category focus sessions, or review past performance.</p>
                </div>
                <div class="d-flex gap-3 align-items-center bg-white bg-opacity-10 p-3 rounded-4 border border-white border-opacity-10">
                    <div class="text-center px-2">
                        <div class="text-white-50 text-2xs text-uppercase fw-bold">Tests Taken</div>
                        <div class="fs-2 fw-bold text-white font-monospace">{{ $totalTests }}</div>
                    </div>
                    <div class="vr bg-white opacity-25"></div>
                    <div class="text-center px-2">
                        <div class="text-white-50 text-2xs text-uppercase fw-bold">Pass Rate</div>
                        <div class="fs-2 fw-bold text-emerald-400 text-success font-monospace">
                            {{ $totalTests > 0 ? round(($passedTests / $totalTests) * 100) : 0 }}%
                        </div>
                    </div>
                    <div class="vr bg-white opacity-25"></div>
                    <div class="text-center px-2">
                        <div class="text-white-50 text-2xs text-uppercase fw-bold">Best Score</div>
                        <div class="fs-2 fw-bold text-amber-400 text-warning font-monospace">{{ $bestScore }}%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Select Exam Mode Cards -->
<div class="row g-4 mb-4">
    <!-- Full Official Simulation -->
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card exam-card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-primary bg-gradient text-white p-4 border-0">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="badge-icon-box bg-white bg-opacity-20 text-white fs-3">
                        <i class="fa-solid fa-laptop-code"></i>
                    </div>
                    <span class="badge bg-white text-primary fw-bold px-3 py-2 rounded-pill">Recommended</span>
                </div>
                <h4 class="fw-bold mb-1">Full Exam Simulation</h4>
                <p class="text-white-50 small mb-0">Mimics real computer-based theory test</p>
            </div>
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <ul class="list-unstyled mb-4 gap-2 d-flex flex-column">
                    <li class="d-flex align-items-center text-secondary small">
                        <i class="fa-solid fa-circle-check text-success me-2"></i> <strong>20 Random Questions</strong> from all categories
                    </li>
                    <li class="d-flex align-items-center text-secondary small">
                        <i class="fa-solid fa-clock text-primary me-2"></i> <strong>20 Minutes</strong> Strict Countdown Timer
                    </li>
                    <li class="d-flex align-items-center text-secondary small">
                        <i class="fa-solid fa-bookmark text-warning me-2"></i> Flag questions for later review option
                    </li>
                    <li class="d-flex align-items-center text-secondary small">
                        <i class="fa-solid fa-volume-high text-info me-2"></i> Text-to-Speech audio question reader
                    </li>
                </ul>

                <form action="{{ route('student.mock-tests.start') }}" method="GET">
                    <input type="hidden" name="mode" value="full">
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm">
                        <i class="fa-solid fa-play me-2"></i> Start Full Exam (20 Qs)
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Quick Practice Sprint -->
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card exam-card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-info bg-gradient text-white p-4 border-0">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="badge-icon-box bg-white bg-opacity-20 text-white fs-3">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <span class="badge bg-white text-info fw-bold px-3 py-2 rounded-pill">Speed Run</span>
                </div>
                <h4 class="fw-bold mb-1">Quick Sprint Quiz</h4>
                <p class="text-white-50 small mb-0">Fast 10-question warm-up session</p>
            </div>
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <ul class="list-unstyled mb-4 gap-2 d-flex flex-column">
                    <li class="d-flex align-items-center text-secondary small">
                        <i class="fa-solid fa-circle-check text-success me-2"></i> <strong>10 Essential Questions</strong>
                    </li>
                    <li class="d-flex align-items-center text-secondary small">
                        <i class="fa-solid fa-clock text-info me-2"></i> <strong>10 Minutes</strong> duration
                    </li>
                    <li class="d-flex align-items-center text-secondary small">
                        <i class="fa-solid fa-trophy text-warning me-2"></i> Perfect for daily quick practice
                    </li>
                    <li class="d-flex align-items-center text-secondary small">
                        <i class="fa-solid fa-chart-line text-primary me-2"></i> Instant result summary
                    </li>
                </ul>

                <form action="{{ route('student.mock-tests.start') }}" method="GET">
                    <input type="hidden" name="mode" value="quick">
                    <button type="submit" class="btn btn-info text-white w-100 py-3 rounded-pill fw-bold shadow-sm">
                        <i class="fa-solid fa-bolt me-2"></i> Start Quick Sprint (10 Qs)
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Category Mastery Mode -->
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card exam-card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-purple bg-gradient text-white p-4 border-0" style="background: linear-gradient(135deg, #7c3aed 0%, #4c1d95 100%);">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="badge-icon-box bg-white bg-opacity-20 text-white fs-3">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <span class="badge bg-white text-dark fw-bold px-3 py-2 rounded-pill">Topic Focus</span>
                </div>
                <h4 class="fw-bold mb-1">Category Focus Practice</h4>
                <p class="text-white-50 small mb-0">Target specific highway code modules</p>
            </div>
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <form action="{{ route('student.mock-tests.start') }}" method="GET">
                    <input type="hidden" name="mode" value="practice">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark mb-2">Select Topic Category</label>
                        <select name="category" class="form-select form-select-lg rounded-3 shadow-none border-secondary-subtle">
                            <option value="">🔀 All Categories (Mixed)</option>
                            <option value="Road Signs">🛑 Road Signs ({{ $categories['Road Signs'] ?? 0 }} Qs)</option>
                            <option value="Traffic Rules">🚦 Traffic Rules ({{ $categories['Traffic Rules'] ?? 0 }} Qs)</option>
                            <option value="Vehicle Controls">⚙️ Vehicle Controls ({{ $categories['Vehicle Controls'] ?? 0 }} Qs)</option>
                            <option value="Emergencies">🚨 Emergencies & Safety ({{ $categories['Emergencies'] ?? 0 }} Qs)</option>
                            <option value="General">📘 General Driving ({{ $categories['General'] ?? 0 }} Qs)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn text-white w-100 py-3 rounded-pill fw-bold shadow-sm" style="background-color: #7c3aed;">
                        <i class="fa-solid fa-bullseye me-2"></i> Start Focused Practice
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Past Attempt History -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-transparent border-0 py-3 px-4 d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-clock-rotate-left text-primary me-2"></i>Your Examination History</h5>
                <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill">Total: {{ $totalTests }} Completed</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Test Code</th>
                                <th>Date & Time</th>
                                <th>Questions</th>
                                <th>Score & Percentage</th>
                                <th>Time Spent</th>
                                <th>Result</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tests as $test)
                                <tr>
                                    <td class="ps-4">
                                        <span class="font-monospace fw-bold text-primary">{{ $test->test_code }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $test->created_at->format('M d, Y') }}</div>
                                        <div class="small text-muted">{{ $test->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $test->total_questions }} Questions</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold fs-6">{{ $test->score }} / {{ $test->total_questions }}</div>
                                        <div class="progress mt-1" style="height: 6px; width: 100px;">
                                            <div class="progress-bar {{ $test->result === 'pass' ? 'bg-success' : 'bg-danger' }}" style="width: {{ $test->percentage }}%"></div>
                                        </div>
                                        <small class="text-muted font-monospace">{{ number_format($test->percentage, 1) }}%</small>
                                    </td>
                                    <td>
                                        <span class="text-muted"><i class="fa-regular fa-clock me-1"></i> {{ floor($test->time_taken_seconds / 60) }}m {{ $test->time_taken_seconds % 60 }}s</span>
                                    </td>
                                    <td>
                                        @if($test->result === 'pass')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-bold">
                                                <i class="fa-solid fa-circle-check me-1"></i> PASSED
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill fw-bold">
                                                <i class="fa-solid fa-circle-xmark me-1"></i> FAILED
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('student.mock-tests.show', $test->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                                            <i class="fa-solid fa-eye me-1"></i> Review
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <div class="py-4">
                                            <i class="fa-solid fa-file-signature fa-3x text-secondary opacity-50 mb-3"></i>
                                            <h5 class="fw-bold">No Examination History Yet</h5>
                                            <p class="text-muted mb-0">Select an exam mode above to begin your first official driving theory practice test.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($tests->hasPages())
                <div class="card-footer bg-transparent py-3 px-4">
                    {{ $tests->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
