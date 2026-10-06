@extends('layouts.admin')

@section('title', 'Mock Test Review')
@section('page_title', 'Mock Test Review')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.mock-tests.index') }}">Student Tests</a></li>
    <li class="breadcrumb-item active" aria-current="page">Test #{{ $mockTest->id }}</li>
@endsection

@section('content')
<div class="row g-4">
    <!-- Test Overview Card -->
    <div class="col-12 col-xl-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent py-3">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-chart-pie text-primary me-2"></i>Performance Summary</h6>
            </div>
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    @if($mockTest->result === 'pass')
                        <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-check fa-2x"></i>
                        </div>
                        <h4 class="mt-3 text-success fw-bold">PASSED</h4>
                    @else
                        <div class="rounded-circle bg-danger text-white d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-xmark fa-2x"></i>
                        </div>
                        <h4 class="mt-3 text-danger fw-bold">FAILED</h4>
                    @endif
                </div>

                <h2 class="display-6 fw-bold mb-1">{{ $mockTest->score }} <span class="fs-5 text-muted">/ {{ $mockTest->total_questions }}</span></h2>
                <p class="text-muted fw-semibold mb-4">{{ number_format($mockTest->percentage, 1) }}% Accuracy</p>

                <div class="border-top pt-3 text-start">
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fa-solid fa-user me-2 text-primary"></i>Student:</span>
                        <span class="fw-semibold">{{ $mockTest->student->user->name ?? 'N/A' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fa-solid fa-hashtag me-2 text-primary"></i>Test Code:</span>
                        <span class="fw-semibold font-monospace">{{ $mockTest->test_code }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fa-solid fa-stopwatch me-2 text-primary"></i>Time Taken:</span>
                        <span class="fw-semibold">
                            {{ floor($mockTest->time_taken_seconds / 60) }}m {{ $mockTest->time_taken_seconds % 60 }}s
                        </span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted"><i class="fa-solid fa-calendar me-2 text-primary"></i>Completed On:</span>
                        <span class="fw-semibold">{{ $mockTest->created_at->format('d M Y, h:i A') }}</span>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.mock-tests.index') }}" class="btn btn-outline-secondary w-100 rounded-pill">
                        <i class="fa-solid fa-arrow-left me-1"></i> Back to Test List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Question Answers Breakdown -->
    <div class="col-12 col-xl-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-list-check text-primary me-2"></i>Question Evaluation Breakdown</h6>
                <span class="badge bg-light text-dark border">{{ count($mockTest->question_answers ?? []) }} Questions</span>
            </div>
            <div class="card-body p-4">
                @if(!empty($mockTest->question_answers) && is_array($mockTest->question_answers))
                    <div class="d-flex flex-column gap-4">
                        @foreach($mockTest->question_answers as $index => $item)
                            @php
                                $isCorrect = ($item['selected_option'] ?? '') === ($item['correct_option'] ?? '');
                            @endphp
                            <div class="p-3 border rounded-3 {{ $isCorrect ? 'border-success-subtle bg-success-subtle bg-opacity-10' : 'border-danger-subtle bg-danger-subtle bg-opacity-10' }}">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="fw-bold mb-0">
                                        <span class="badge {{ $isCorrect ? 'bg-success' : 'bg-danger' }} me-2">Q{{ $index + 1 }}</span>
                                        {{ $item['question'] ?? 'Question missing' }}
                                    </h6>
                                    @if($isCorrect)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="fa-solid fa-check me-1"></i> Correct</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1"><i class="fa-solid fa-xmark me-1"></i> Incorrect</span>
                                    @endif
                                </div>

                                <div class="row g-2 mt-2">
                                    @foreach(['A', 'B', 'C', 'D'] as $opt)
                                        @php
                                            $optKey = 'option_' . strtolower($opt);
                                            $optText = $item[$optKey] ?? '';
                                            $isSelected = ($item['selected_option'] ?? '') === $opt;
                                            $isCorrectOpt = ($item['correct_option'] ?? '') === $opt;

                                            $cardClass = 'bg-white border text-secondary';
                                            if ($isCorrectOpt) {
                                                $cardClass = 'bg-success text-white border-success shadow-sm';
                                            } elseif ($isSelected && !$isCorrect) {
                                                $cardClass = 'bg-danger text-white border-danger shadow-sm';
                                            }
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="p-2 rounded-2 border {{ $cardClass }} d-flex align-items-center">
                                                <span class="fw-bold me-2">{{ $opt }}.</span>
                                                <span class="flex-grow-1 text-truncate">{{ $optText }}</span>
                                                @if($isSelected && $isCorrectOpt)
                                                    <i class="fa-solid fa-check-double ms-2" title="Student chose correctly"></i>
                                                @elseif($isSelected)
                                                    <i class="fa-solid fa-circle-xmark ms-2" title="Student choice"></i>
                                                @elseif($isCorrectOpt)
                                                    <i class="fa-solid fa-check ms-2" title="Correct Answer"></i>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @if(!empty($item['explanation']))
                                    <div class="mt-3 p-2 bg-light rounded text-muted small border">
                                        <i class="fa-solid fa-lightbulb text-warning me-1"></i> <strong>Explanation:</strong> {{ $item['explanation'] }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fa-solid fa-circle-question fa-3x mb-3 text-secondary"></i>
                        <p>No detailed breakdown available for this test session.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
