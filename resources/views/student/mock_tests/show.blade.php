@extends('layouts.admin')

@section('title', 'Exam Result & Evaluation')
@section('page_title', 'Mock Examination Performance Summary')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('student.mock-tests.index') }}">Exam Center</a></li>
    <li class="breadcrumb-item active" aria-current="page">Result Review</li>
@endsection

@push('styles')
<style>
    .result-banner-pass {
        background: linear-gradient(135deg, #065f46 0%, #047857 100%);
        color: #ffffff;
    }
    .result-banner-fail {
        background: linear-gradient(135deg, #991b1b 0%, #dc2626 100%);
        color: #ffffff;
    }
    .cert-stamp {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 4px double rgba(255,255,255,0.4);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(4px);
    }
    .review-card {
        transition: all 0.2s;
    }
    .review-card.hidden {
        display: none !important;
    }
    @media print {
        sidebar, header, .btn-print-hide { display: none !important; }
        .main-content { margin: 0 !important; padding: 0 !important; }
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-10">

        <!-- Result Banner Header -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 {{ $mockTest->result === 'pass' ? 'result-banner-pass' : 'result-banner-fail' }}">
            <div class="card-body p-4 p-md-5 text-center position-relative">
                
                <div class="cert-stamp mb-3">
                    @if($mockTest->result === 'pass')
                        <i class="fa-solid fa-trophy fa-3x text-warning"></i>
                    @else
                        <i class="fa-solid fa-shield-halved fa-3x text-white-50"></i>
                    @endif
                </div>

                @if($mockTest->result === 'pass')
                    <h1 class="fw-extrabold mb-2 display-6">EXAMINATION PASSED</h1>
                    <p class="fs-5 text-white-50 max-w-xl mx-auto mb-4">Congratulations! You achieved the required 80% threshold for official driving theory proficiency.</p>
                @else
                    <h1 class="fw-extrabold mb-2 display-6">EXAMINATION NOT PASSED</h1>
                    <p class="fs-5 text-white-50 max-w-xl mx-auto mb-4">You did not reach the 80% pass mark this time. Review your category errors below and attempt another practice exam.</p>
                @endif

                <!-- Performance KPI Grid -->
                <div class="row justify-content-center g-3 pt-4 border-top border-white border-opacity-20 max-w-3xl mx-auto">
                    <div class="col-6 col-md-3">
                        <div class="text-white-50 text-2xs text-uppercase fw-bold mb-1">Final Score</div>
                        <div class="fs-3 fw-bold font-monospace">{{ $mockTest->score }} / {{ $mockTest->total_questions }}</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-white-50 text-2xs text-uppercase fw-bold mb-1">Score Accuracy</div>
                        <div class="fs-3 fw-bold font-monospace">{{ number_format($mockTest->percentage, 1) }}%</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-white-50 text-2xs text-uppercase fw-bold mb-1">Time Elapsed</div>
                        <div class="fs-3 fw-bold font-monospace">{{ floor($mockTest->time_taken_seconds / 60) }}m {{ $mockTest->time_taken_seconds % 60 }}s</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-white-50 text-2xs text-uppercase fw-bold mb-1">Test Code</div>
                        <div class="fs-5 fw-bold font-monospace mt-1">{{ $mockTest->test_code }}</div>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-center gap-3 flex-wrap btn-print-hide">
                    <a href="{{ route('student.mock-tests.index') }}" class="btn btn-light rounded-pill px-4 fw-bold">
                        <i class="fa-solid fa-arrow-left me-2"></i> Exam Center
                    </a>
                    <form action="{{ route('student.mock-tests.start') }}" method="GET" class="d-inline">
                        <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm">
                            <i class="fa-solid fa-rotate-right me-2"></i> Retake New Test
                        </button>
                    </form>
                    <button type="button" onclick="window.print()" class="btn btn-outline-light rounded-pill px-4 fw-bold">
                        <i class="fa-solid fa-print me-2"></i> Print Certificate
                    </button>
                </div>
            </div>
        </div>

        <!-- Category Readiness Breakdown -->
        @if(!empty($categoryBreakdown) && is_array($categoryBreakdown))
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-chart-pie text-primary me-2"></i>Category Mastery Breakdown</h5>
                    <p class="text-muted small mb-0">Evaluate your performance across highway code topics</p>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        @foreach($categoryBreakdown as $catName => $stats)
                            @php
                                $catPercentage = $stats['total'] > 0 ? round(($stats['correct'] / $stats['total']) * 100) : 0;
                                $barClass = $catPercentage >= 80 ? 'bg-success' : ($catPercentage >= 50 ? 'bg-warning' : 'bg-danger');
                            @endphp
                            <div class="col-12 col-md-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold text-dark">{{ $catName }}</span>
                                        <span class="fw-bold font-monospace {{ $catPercentage >= 80 ? 'text-success' : 'text-danger' }}">
                                            {{ $stats['correct'] }} / {{ $stats['total'] }} ({{ $catPercentage }}%)
                                        </span>
                                    </div>
                                    <div class="progress rounded-pill" style="height: 10px;">
                                        <div class="progress-bar {{ $barClass }}" role="progressbar" style="width: {{ $catPercentage }}%;"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Filter Tabs for Questions -->
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2 btn-print-hide">
            <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-list-check text-primary me-2"></i>Question-by-Question Evaluation</h5>

            <div class="btn-group p-1 bg-light rounded-pill border" role="group" id="filterButtonGroup">
                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold filter-btn active" data-filter="all">
                    All Questions ({{ count($mockTest->question_answers ?? []) }})
                </button>
                <button type="button" class="btn btn-sm btn-light rounded-pill px-3 text-secondary filter-btn" data-filter="incorrect">
                    ❌ Incorrect
                </button>
                <button type="button" class="btn btn-sm btn-light rounded-pill px-3 text-secondary filter-btn" data-filter="flagged">
                    🚩 Flagged
                </button>
                <button type="button" class="btn btn-sm btn-light rounded-pill px-3 text-secondary filter-btn" data-filter="correct">
                    ✓ Correct
                </button>
            </div>
        </div>

        <!-- Detailed Question List -->
        <div class="d-flex flex-column gap-3 mb-5" id="questionsList">
            @if(!empty($mockTest->question_answers) && is_array($mockTest->question_answers))
                @foreach($mockTest->question_answers as $index => $item)
                    @php
                        $isCorrect = ($item['selected_option'] ?? '') === ($item['correct_option'] ?? '');
                        $isSkipped = empty($item['selected_option']);
                        $isFlagged = !empty($item['is_flagged']);

                        $statusFilter = $isCorrect ? 'correct' : 'incorrect';
                    @endphp
                    <div class="card border-0 shadow-sm rounded-4 review-card {{ $isSkipped ? '' : ($isCorrect ? 'border-start border-success border-4' : 'border-start border-danger border-4') }}"
                         data-status="{{ $statusFilter }}"
                         data-flagged="{{ $isFlagged ? 'true' : 'false' }}">
                        
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="fw-bold text-dark mb-0 lh-base">
                                    <span class="badge {{ $isCorrect ? 'bg-success' : 'bg-danger' }} me-2">Q{{ $index + 1 }}</span>
                                    {{ $item['question'] ?? 'Question text unavailable' }}
                                </h5>

                                <div class="d-flex align-items-center gap-2">
                                    @if($isFlagged)
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1 rounded-pill">
                                            <i class="fa-solid fa-bookmark me-1"></i> Flagged
                                        </span>
                                    @endif
                                    @if($isSkipped)
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-1 rounded-pill">
                                            Skipped
                                        </span>
                                    @elseif($isCorrect)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill fw-bold">
                                            <i class="fa-solid fa-check me-1"></i> Correct
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill fw-bold">
                                            <i class="fa-solid fa-xmark me-1"></i> Incorrect
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <span class="badge bg-light text-secondary border rounded-pill px-3 py-1">Category: {{ $item['category'] ?? 'General' }}</span>
                            </div>

                            @if(!empty($item['image']))
                                <div class="mb-3 bg-light p-2 rounded-3 text-center border" style="max-width: 300px;">
                                    <img src="{{ asset('storage/'.$item['image']) }}" alt="Visual prompt" class="img-fluid rounded">
                                </div>
                            @endif

                            <!-- Options List Grid -->
                            <div class="row g-2 mt-2">
                                @foreach(['A', 'B', 'C', 'D'] as $opt)
                                    @php
                                        $optKey = 'option_' . strtolower($opt);
                                        $optText = $item[$optKey] ?? '';
                                        $isSelected = ($item['selected_option'] ?? '') === $opt;
                                        $isCorrectOpt = ($item['correct_option'] ?? '') === $opt;

                                        $cardStyle = 'bg-light text-secondary border-light';

                                        if ($isCorrectOpt) {
                                            $cardStyle = 'bg-success bg-opacity-10 text-success border-success fw-bold shadow-sm';
                                        } elseif ($isSelected && !$isCorrect) {
                                            $cardStyle = 'bg-danger bg-opacity-10 text-danger border-danger fw-bold shadow-sm';
                                        }
                                    @endphp
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-3 border {{ $cardStyle }} d-flex align-items-center justify-content-between h-100">
                                            <div class="d-flex align-items-center me-2">
                                                <span class="fw-bold fs-5 me-3 font-monospace">{{ $opt }}.</span>
                                                <span class="fs-6">{{ $optText }}</span>
                                            </div>

                                            <div>
                                                @if($isSelected && $isCorrectOpt)
                                                    <span class="badge bg-success"><i class="fa-solid fa-check me-1"></i> Your Choice & Correct</span>
                                                @elseif($isSelected)
                                                    <span class="badge bg-danger"><i class="fa-solid fa-xmark me-1"></i> Your Choice</span>
                                                @elseif($isCorrectOpt)
                                                    <span class="badge bg-success"><i class="fa-solid fa-check me-1"></i> Correct Answer</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Highway Code Explanation -->
                            @if(!empty($item['explanation']))
                                <div class="mt-4 p-3 bg-warning bg-opacity-10 text-dark rounded-3 border border-warning border-opacity-20 d-flex align-items-start gap-3">
                                    <i class="fa-solid fa-lightbulb text-warning fs-3 pt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1 text-dark">Highway Code Explanation:</h6>
                                        <p class="mb-0 text-secondary small lh-base">{{ $item['explanation'] }}</p>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                @endforeach
            @endif
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const reviewCards = document.querySelectorAll('.review-card');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => {
                b.classList.remove('btn-primary', 'active');
                b.classList.add('btn-light', 'text-secondary');
            });
            this.classList.add('btn-primary', 'active');
            this.classList.remove('btn-light', 'text-secondary');

            const filter = this.getAttribute('data-filter');

            reviewCards.forEach(card => {
                const status = card.getAttribute('data-status');
                const isFlagged = card.getAttribute('data-flagged') === 'true';

                if (filter === 'all') {
                    card.classList.remove('hidden');
                } else if (filter === 'correct' && status === 'correct') {
                    card.classList.remove('hidden');
                } else if (filter === 'incorrect' && status === 'incorrect') {
                    card.classList.remove('hidden');
                } else if (filter === 'flagged' && isFlagged) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        });
    });
});
</script>
@endpush
