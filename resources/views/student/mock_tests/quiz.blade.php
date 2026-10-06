@extends('layouts.admin')

@section('title', 'Driving Theory Mock Examination')
@section('page_title', 'Computer-Based Mock Test Session')

@push('styles')
<style>
    /* Full screen testing console */
    .quiz-container {
        font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
    }
    .question-card { display: none !important; }
    .question-card.active { display: block !important; animation: quizFadeIn 0.25s ease-out; }

    @keyframes quizFadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .option-label {
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px 20px;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        display: flex;
        align-items: center;
        background: #ffffff;
        position: relative;
    }
    .option-label:hover {
        border-color: #3b82f6;
        background: #f8fafc;
        transform: translateX(4px);
    }
    .option-input:checked + .option-label {
        border-color: #2563eb;
        background: #eff6ff;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
    }
    .option-letter {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: #f1f5f9;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: #334155;
        margin-right: 16px;
        font-size: 1.15rem;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    .option-input:checked + .option-label .option-letter {
        background: #2563eb;
        color: #ffffff;
    }
    .option-text {
        font-size: 1.05rem;
        color: #1e293b;
        font-weight: 500;
        line-height: 1.5;
    }

    .selected-tag {
        display: none;
        margin-left: auto;
        padding: 4px 12px;
        border-radius: 20px;
        background-color: #2563eb;
        color: #ffffff;
        font-size: 0.85rem;
        font-weight: 700;
    }
    .option-input:checked + .option-label .selected-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* Selection Banner Styling */
    .selection-banner {
        border-radius: 12px;
        padding: 14px 18px;
        transition: all 0.25s ease;
    }
    .selection-banner.unselected {
        background-color: #fffbebf5;
        border: 1.5 solid #fde68a;
        color: #92400e;
    }
    .selection-banner.selected {
        background-color: #f0fdf4;
        border: 1.5 solid #bbf7d0;
        color: #166534;
    }

    /* Matrix button styling */
    .q-matrix-btn {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        position: relative;
        transition: all 0.15s;
        border: 2px solid #cbd5e1;
        background-color: #f8fafc;
        color: #475569;
    }
    .q-matrix-btn:hover {
        border-color: #2563eb;
        transform: scale(1.08);
    }
    .q-matrix-btn.current {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.3);
    }
    .q-matrix-btn.answered {
        background-color: #10b981 !important;
        border-color: #059669 !important;
        color: #ffffff !important;
    }
    .q-matrix-btn.flagged::after {
        content: '';
        position: absolute;
        top: -4px;
        right: -4px;
        width: 14px;
        height: 14px;
        background-color: #f59e0b;
        border: 2px solid #ffffff;
        border-radius: 50%;
    }

    /* Timer pulse */
    .timer-critical {
        animation: pulseWarning 1s infinite alternate;
    }
    @keyframes pulseWarning {
        from { background-color: #ef4444; color: #ffffff; }
        to { background-color: #b91c1c; color: #ffffff; }
    }

    /* Accessibility font size class */
    .font-large .question-title { font-size: 1.5rem !important; }
    .font-large .option-text { font-size: 1.25rem !important; }
</style>
@endpush

@section('content')
<div class="quiz-container" id="quizAppContainer">

    <form id="quizForm" action="{{ route('student.mock-tests.submit') }}" method="POST">
        @csrf
        <input type="hidden" name="time_taken_seconds" id="time_taken" value="0">
        @foreach($questions as $q)
            <input type="hidden" name="question_ids[]" value="{{ $q->id }}">
        @endforeach

        <!-- Top Console Control Bar -->
        <div class="card border-0 shadow-sm mb-4 position-sticky top-0 rounded-4 overflow-hidden" style="z-index: 1000; background: #ffffff;">
            <div class="card-body py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                
                <!-- Left: Exam Title & Accessibility -->
                <div class="d-flex align-items-center gap-3">
                    <div>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-bold">
                            <i class="fa-solid fa-graduation-cap me-1"></i> {{ strtoupper($mode ?? 'FULL') }} EXAM
                        </span>
                        @if(!empty($category))
                            <span class="badge bg-purple-subtle text-purple border border-purple-subtle rounded-pill px-3 py-1 fw-bold ms-1" style="background:#f3e8ff; color:#7e22ce;">
                                {{ $category }}
                            </span>
                        @endif
                    </div>

                    <!-- Audio Reader & Font Resize -->
                    <div class="d-flex align-items-center gap-1 bg-light p-1 rounded-pill border">
                        <button type="button" id="btnAudio" class="btn btn-sm btn-light rounded-circle" title="Read Question Aloud">
                            <i class="fa-solid fa-volume-high text-primary"></i>
                        </button>
                        <button type="button" id="btnFontToggle" class="btn btn-sm btn-light rounded-circle px-2 fw-bold" title="Toggle Font Size">
                            A+
                        </button>
                    </div>
                </div>

                <!-- Center: Question Tracker & Global Selection Status -->
                <div class="text-center">
                    <span class="text-muted small text-uppercase fw-bold d-block">Question Progress</span>
                    <span class="fw-extrabold fs-4 font-monospace text-primary" id="currentQDisplay">1</span>
                    <span class="fs-5 text-muted font-monospace"> / {{ count($questions) }}</span>
                </div>

                <!-- Right: Flag Button & Countdown Timer -->
                <div class="d-flex align-items-center gap-3">
                    <!-- Flag for review -->
                    <button type="button" id="btnFlag" class="btn btn-outline-warning rounded-pill px-3 fw-bold d-flex align-items-center gap-2">
                        <i class="fa-regular fa-bookmark" id="flagIcon"></i>
                        <span id="flagText">Flag for Review</span>
                    </button>

                    <!-- Timer Pill -->
                    <div id="timerContainer" class="d-inline-flex align-items-center bg-dark text-white rounded-pill px-4 py-2 shadow-sm">
                        <i class="fa-solid fa-stopwatch fa-lg me-2 text-warning"></i>
                        <span id="timerDisplay" class="fw-bold fs-5 font-monospace">{{ sprintf('%02d:00', $timerMinutes ?? 20) }}</span>
                    </div>
                </div>
            </div>

            <!-- Live Progress Bar -->
            <div class="progress rounded-0 bg-light" style="height: 5px;">
                <div id="progressBar" class="progress-bar bg-primary transition-all" role="progressbar" style="width: 5%;"></div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Main Question Area -->
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4 p-md-5">

                        @foreach($questions as $index => $q)
                            <div class="question-card {{ $index === 0 ? 'active' : '' }}" style="{{ $index === 0 ? 'display: block;' : 'display: none;' }}" id="q-card-{{ $index }}" data-qid="{{ $q->id }}">

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill fw-semibold">
                                        Category: {{ $q->category ?? 'General Safety' }}
                                    </span>
                                    <span class="text-muted small">Shortcuts: <strong>1-4</strong> select option, <strong>F</strong> flag</span>
                                </div>

                                <!-- Dynamic Answer Selection Status Alert Bar -->
                                <div class="selection-banner unselected mb-4 border d-flex align-items-center justify-content-between" id="status-banner-{{ $index }}">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-circle-exclamation fs-5 text-warning" id="status-icon-{{ $index }}"></i>
                                        <span class="fw-bold fs-6" id="status-text-{{ $index }}">Not Answered Yet</span>
                                    </div>
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold" id="status-badge-{{ $index }}">
                                        Select an option below
                                    </span>
                                </div>

                                <!-- Question Title -->
                                <h3 class="fw-bold mb-4 question-title text-dark lh-base" id="q-text-{{ $index }}">
                                    <span class="text-primary me-2">Q{{ $index + 1 }}.</span>
                                    {{ $q->question }}
                                </h3>

                                <!-- Optional Image or Sign Graphics -->
                                @if($q->image)
                                    <div class="text-center mb-4 bg-light p-3 rounded-4 border">
                                        <img src="{{ asset('storage/'.$q->image) }}" alt="Road Hazard Visual" class="img-fluid rounded-3 shadow-sm" style="max-height: 220px;">
                                    </div>
                                @endif

                                <!-- Options List -->
                                <div class="row g-3" id="q-options-{{ $index }}">
                                    @foreach(['A', 'B', 'C', 'D'] as $opt)
                                        @php $optValue = $q->{'option_'.strtolower($opt)}; @endphp
                                        <div class="col-12">
                                            <input type="radio"
                                                   name="answers[{{ $q->id }}]"
                                                   id="q_{{ $q->id }}_{{ $opt }}"
                                                   value="{{ $opt }}"
                                                   class="btn-check option-input"
                                                   data-index="{{ $index }}"
                                                   data-letter="{{ $opt }}"
                                                   data-text="{{ e($optValue) }}">
                                            <label class="option-label w-100" for="q_{{ $q->id }}_{{ $opt }}">
                                                <span class="option-letter">{{ $opt }}</span>
                                                <span class="option-text">{{ $optValue }}</span>
                                                <span class="selected-tag"><i class="fa-solid fa-check"></i> Selected</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Hidden Flag Checkbox -->
                                <input type="checkbox" name="flagged[{{ $q->id }}]" id="flag_chk_{{ $q->id }}" value="1" class="d-none flag-input">
                            </div>
                        @endforeach

                    </div>
                </div>

                <!-- Navigation Controls -->
                <div class="card border-0 shadow-sm rounded-4 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-bold" id="btnPrev" disabled>
                            <i class="fa-solid fa-arrow-left me-2"></i> Previous
                        </button>

                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm" id="btnNext">
                                Next Question <i class="fa-solid fa-arrow-right ms-2"></i>
                            </button>

                            <button type="button" class="btn btn-success rounded-pill px-5 fw-bold shadow-sm d-none" id="btnSubmitModalTrigger">
                                Submit Exam <i class="fa-solid fa-paper-plane ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Question Matrix Palette -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 position-sticky" style="top: 95px;">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2">
                        <h6 class="fw-bold mb-1 text-dark"><i class="fa-solid fa-grip text-primary me-2"></i>Question Navigator</h6>
                        <small class="text-muted">Click any number to jump directly to question</small>
                    </div>
                    <div class="card-body p-4">
                        
                        <!-- Question Matrix Grid -->
                        <div class="d-flex flex-wrap gap-2 mb-4" id="matrixGrid">
                            @foreach($questions as $index => $q)
                                <button type="button" 
                                        class="q-matrix-btn {{ $index === 0 ? 'current' : '' }}" 
                                        id="matrix-btn-{{ $index }}" 
                                        data-target="{{ $index }}">
                                    {{ $index + 1 }}
                                </button>
                            @endforeach
                        </div>

                        <!-- Legend Status -->
                        <div class="p-3 bg-light rounded-3 border">
                            <h6 class="fw-bold text-dark text-uppercase text-2xs mb-2">Status Palette Legend</h6>
                            <div class="d-flex flex-column gap-2 small">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="q-matrix-btn answered" style="width: 24px; height: 24px; font-size: 10px;">✓</span>
                                    <span>Answered Question</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="q-matrix-btn flagged" style="width: 24px; height: 24px;"></span>
                                    <span>Flagged for Review</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="q-matrix-btn current" style="width: 24px; height: 24px;"></span>
                                    <span>Current Question</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="q-matrix-btn" style="width: 24px; height: 24px;"></span>
                                    <span>Unanswered Question</span>
                                </div>
                            </div>
                        </div>

                        <!-- Live Counters -->
                        <div class="row g-2 text-center mt-3">
                            <div class="col-4">
                                <div class="p-2 border rounded-3 bg-success-subtle text-success">
                                    <div class="fw-bold font-monospace fs-5" id="countAnswered">0</div>
                                    <div class="text-2xs text-uppercase fw-semibold">Answered</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 border rounded-3 bg-warning-subtle text-warning">
                                    <div class="fw-bold font-monospace fs-5" id="countFlagged">0</div>
                                    <div class="text-2xs text-uppercase fw-semibold">Flagged</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 border rounded-3 bg-light text-secondary">
                                    <div class="fw-bold font-monospace fs-5" id="countRemaining">{{ count($questions) }}</div>
                                    <div class="text-2xs text-uppercase fw-semibold">Remaining</div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="button" class="btn btn-outline-danger w-100 rounded-pill fw-bold" id="btnFinishEarly">
                                <i class="fa-solid fa-flag-checkered me-2"></i> Finish & Submit Exam
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal Confirmation Before Submit -->
<div class="modal fade" id="submitConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 bg-primary text-white p-4">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-paper-plane me-2"></i>Confirm Final Submission</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-secondary fs-6 mb-4">Are you sure you want to finish and submit your theory examination now?</p>

                <div class="card bg-light border-0 p-3 mb-3 rounded-3">
                    <div class="row text-center g-2">
                        <div class="col-4">
                            <div class="text-muted small">Answered</div>
                            <div class="fw-bold text-success fs-4 font-monospace" id="modalAnsCount">0</div>
                        </div>
                        <div class="col-4">
                            <div class="text-muted small">Flagged</div>
                            <div class="fw-bold text-warning fs-4 font-monospace" id="modalFlagCount">0</div>
                        </div>
                        <div class="col-4">
                            <div class="text-muted small">Unanswered</div>
                            <div class="fw-bold text-danger fs-4 font-monospace" id="modalUnansCount">0</div>
                        </div>
                    </div>
                </div>

                <div id="unansweredWarningAlert" class="alert alert-warning border-0 rounded-3 d-none">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> You have <strong id="warnUnansNum">0</strong> unanswered question(s). Unanswered questions will be marked incorrect.
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Return to Exam</button>
                <button type="button" id="btnConfirmSubmit" class="btn btn-success rounded-pill px-5 fw-bold shadow-sm">
                    Yes, Submit Now
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const totalQuestions = {{ count($questions) }};
    const timerMinutes = {{ $timerMinutes ?? 20 }};
    let currentIndex = 0;
    let totalTimeLimit = timerMinutes * 60;
    let timeElapsed = 0;

    // Element references
    const timerDisplay = document.getElementById('timerDisplay');
    const timerContainer = document.getElementById('timerContainer');
    const timeInput = document.getElementById('time_taken');
    const quizForm = document.getElementById('quizForm');
    const btnPrev = document.getElementById('btnPrev');
    const btnNext = document.getElementById('btnNext');
    const btnSubmitModalTrigger = document.getElementById('btnSubmitModalTrigger');
    const btnFinishEarly = document.getElementById('btnFinishEarly');
    const btnFlag = document.getElementById('btnFlag');
    const flagIcon = document.getElementById('flagIcon');
    const flagText = document.getElementById('flagText');
    const btnAudio = document.getElementById('btnAudio');
    const btnFontToggle = document.getElementById('btnFontToggle');
    const submitModal = new bootstrap.Modal(document.getElementById('submitConfirmModal'));
    const btnConfirmSubmit = document.getElementById('btnConfirmSubmit');

    // Timer Countdown Logic
    const timerInterval = setInterval(function() {
        timeElapsed++;
        timeInput.value = timeElapsed;

        let timeRemaining = totalTimeLimit - timeElapsed;
        if (timeRemaining <= 0) {
            clearInterval(timerInterval);
            Swal.fire({
                title: 'Time Expired!',
                text: 'Your exam time has ended. Your responses are being submitted automatically.',
                icon: 'warning',
                showConfirmButton: false,
                timer: 3500
            }).then(() => {
                quizForm.submit();
            });
        } else {
            let m = Math.floor(timeRemaining / 60).toString().padStart(2, '0');
            let s = (timeRemaining % 60).toString().padStart(2, '0');
            timerDisplay.textContent = `${m}:${s}`;

            if(timeRemaining <= 120) { // low time warning (under 2 min)
                timerContainer.classList.add('timer-critical');
            }
        }
    }, 1000);

    // UI State Update
    function updateUI() {
        // Hide all cards, show active
        document.querySelectorAll('.question-card').forEach(el => {
            el.classList.remove('active');
            el.style.display = 'none';
        });
        const activeCard = document.getElementById(`q-card-${currentIndex}`);
        activeCard.classList.add('active');
        activeCard.style.display = 'block';

        // Update display counter
        document.getElementById('currentQDisplay').textContent = currentIndex + 1;
        document.getElementById('progressBar').style.width = `${((currentIndex + 1) / totalQuestions) * 100}%`;

        // Buttons state
        btnPrev.disabled = (currentIndex === 0);

        if (currentIndex === totalQuestions - 1) {
            btnNext.classList.add('d-none');
            btnSubmitModalTrigger.classList.remove('d-none');
        } else {
            btnNext.classList.remove('d-none');
            btnSubmitModalTrigger.classList.add('d-none');
        }

        // Matrix Grid Highlight
        document.querySelectorAll('.q-matrix-btn').forEach((btn, idx) => {
            if(idx === currentIndex) {
                btn.classList.add('current');
            } else {
                btn.classList.remove('current');
            }
        });

        // Flag button state for current question
        const currentQId = activeCard.getAttribute('data-qid');
        const flagChk = document.getElementById(`flag_chk_${currentQId}`);
        if(flagChk && flagChk.checked) {
            btnFlag.classList.remove('btn-outline-warning');
            btnFlag.classList.add('btn-warning', 'text-dark');
            flagIcon.className = 'fa-solid fa-bookmark';
            flagText.textContent = 'Flagged';
        } else {
            btnFlag.classList.add('btn-outline-warning');
            btnFlag.classList.remove('btn-warning', 'text-dark');
            flagIcon.className = 'fa-regular fa-bookmark';
            flagText.textContent = 'Flag for Review';
        }

        updateCurrentSelectionBanner(currentIndex);
        recalculateStats();
    }

    // Update Banner for Question Selection Status
    function updateCurrentSelectionBanner(idx) {
        const card = document.getElementById(`q-card-${idx}`);
        const qid = card.getAttribute('data-qid');
        const checkedOption = card.querySelector(`input[name="answers[${qid}]"]:checked`);

        const banner = document.getElementById(`status-banner-${idx}`);
        const icon = document.getElementById(`status-icon-${idx}`);
        const text = document.getElementById(`status-text-${idx}`);
        const badge = document.getElementById(`status-badge-${idx}`);

        if (checkedOption) {
            const letter = checkedOption.getAttribute('data-letter');
            banner.className = 'selection-banner selected mb-4 border d-flex align-items-center justify-content-between';
            icon.className = 'fa-solid fa-circle-check fs-5 text-success';
            text.innerHTML = `<strong>Status:</strong> Answer Selected — <span class="text-success fw-bold">Option ${letter}</span>`;
            badge.className = 'badge bg-success text-white px-3 py-2 rounded-pill fw-bold';
            badge.innerHTML = `<i class="fa-solid fa-check me-1"></i> Option ${letter} Selected`;
        } else {
            banner.className = 'selection-banner unselected mb-4 border d-flex align-items-center justify-content-between';
            icon.className = 'fa-solid fa-circle-exclamation fs-5 text-warning';
            text.innerHTML = `<strong>Status:</strong> Not Answered Yet`;
            badge.className = 'badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold';
            badge.innerHTML = `Please select an option below`;
        }
    }

    // Recalculate Matrix & Counters
    function recalculateStats() {
        let answeredCount = 0;
        let flaggedCount = 0;

        document.querySelectorAll('.question-card').forEach((card, idx) => {
            const qid = card.getAttribute('data-qid');
            const checkedOption = card.querySelector(`input[name="answers[${qid}]"]:checked`);
            const flagChk = document.getElementById(`flag_chk_${qid}`);
            const matrixBtn = document.getElementById(`matrix-btn-${idx}`);

            if(checkedOption) {
                answeredCount++;
                matrixBtn.classList.add('answered');
            } else {
                matrixBtn.classList.remove('answered');
            }

            if(flagChk && flagChk.checked) {
                flaggedCount++;
                matrixBtn.classList.add('flagged');
            } else {
                matrixBtn.classList.remove('flagged');
            }
        });

        document.getElementById('countAnswered').textContent = answeredCount;
        document.getElementById('countFlagged').textContent = flaggedCount;
        document.getElementById('countRemaining').textContent = totalQuestions - answeredCount;
    }

    // Prev / Next Navigation
    btnPrev.addEventListener('click', () => {
        if (currentIndex > 0) { currentIndex--; updateUI(); }
    });

    btnNext.addEventListener('click', () => {
        // Enforce selecting an option before moving to the next question
        const activeCard = document.getElementById(`q-card-${currentIndex}`);
        const qid = activeCard.getAttribute('data-qid');
        const checkedOption = activeCard.querySelector(`input[name="answers[${qid}]"]:checked`);

        if (!checkedOption) {
            Swal.fire({
                title: 'No Answer Selected',
                text: 'Please select an answer before proceeding to the next question.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        if (currentIndex < totalQuestions - 1) { currentIndex++; updateUI(); }
    });

    // Matrix button direct jump
    document.querySelectorAll('.q-matrix-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetIndex = parseInt(this.getAttribute('data-target'));

            // If they are trying to go forward, check if current is answered
            if (targetIndex > currentIndex) {
                const activeCard = document.getElementById(`q-card-${currentIndex}`);
                const qid = activeCard.getAttribute('data-qid');
                const checkedOption = activeCard.querySelector(`input[name="answers[${qid}]"]:checked`);

                if (!checkedOption) {
                    Swal.fire({
                        title: 'No Answer Selected',
                        text: 'Please select an answer for the current question before moving forward.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
            }

            currentIndex = targetIndex;
            updateUI();
        });
    });

    // Flag toggle click
    btnFlag.addEventListener('click', () => {
        const activeCard = document.getElementById(`q-card-${currentIndex}`);
        const qid = activeCard.getAttribute('data-qid');
        const flagChk = document.getElementById(`flag_chk_${qid}`);
        if(flagChk) {
            flagChk.checked = !flagChk.checked;
            updateUI();
        }
    });

    // Radio option select event
    document.querySelectorAll('.option-input').forEach(input => {
        input.addEventListener('change', function() {
            const idx = parseInt(this.getAttribute('data-index'));
            updateCurrentSelectionBanner(idx);
            recalculateStats();
        });
    });

    // Keyboard Shortcuts (1-4 or A-D select options; F flags; Left/Right arrows navigate)
    document.addEventListener('keydown', function(e) {
        if (['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) return;

        const activeCard = document.getElementById(`q-card-${currentIndex}`);
        const qid = activeCard.getAttribute('data-qid');

        if (e.key === 'ArrowRight' && currentIndex < totalQuestions - 1) {
            const checkedOption = activeCard.querySelector(`input[name="answers[${qid}]"]:checked`);
            if (!checkedOption) {
                Swal.fire({
                    title: 'No Answer Selected',
                    text: 'Please select an answer before proceeding to the next question.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }
            currentIndex++; updateUI();
        } else if (e.key === 'ArrowLeft' && currentIndex > 0) {
            currentIndex--; updateUI();
        } else if (e.key.toLowerCase() === 'f') {
            btnFlag.click();
        } else {
            const keyMap = { '1': 'A', '2': 'B', '3': 'C', '4': 'D', 'a': 'A', 'b': 'B', 'c': 'C', 'd': 'D' };
            const optLetter = keyMap[e.key.toLowerCase()];
            if (optLetter) {
                const radio = document.getElementById(`q_${qid}_${optLetter}`);
                if (radio) {
                    radio.checked = true;
                    radio.dispatchEvent(new Event('change'));
                }
            }
        }
    });

    // Font size toggle
    let isLargeFont = false;
    btnFontToggle.addEventListener('click', () => {
        isLargeFont = !isLargeFont;
        document.getElementById('quizAppContainer').classList.toggle('font-large', isLargeFont);
    });

    // Text to Speech (Audio Reader)
    btnAudio.addEventListener('click', () => {
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel(); // Stop any active speech
            const activeCard = document.getElementById(`q-card-${currentIndex}`);
            const questionText = activeCard.querySelector('.question-title').innerText;
            const options = Array.from(activeCard.querySelectorAll('.option-text')).map((opt, i) => `Option ${String.fromCharCode(65 + i)}: ${opt.innerText}`).join('. ');
            
            const utterance = new SpeechSynthesisUtterance(`${questionText}. ${options}`);
            utterance.rate = 0.95;
            window.speechSynthesis.speak(utterance);
        } else {
            Swal.fire('Not Supported', 'Text-to-speech is not supported in this browser.', 'info');
        }
    });

    // Trigger submit modal
    function openSubmitModal() {
        // Validation for the current question
        const activeCard = document.getElementById(`q-card-${currentIndex}`);
        const qid = activeCard.getAttribute('data-qid');
        const checkedOption = activeCard.querySelector(`input[name="answers[${qid}]"]:checked`);

        if (!checkedOption) {
            Swal.fire({
                title: 'No Answer Selected',
                text: 'Please select an answer for the current question before submitting.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        let ansCount = 0;
        let flagCount = 0;
        document.querySelectorAll('.question-card').forEach(card => {
            const qid = card.getAttribute('data-qid');
            if (card.querySelector(`input[name="answers[${qid}]"]:checked`)) ansCount++;
            if (document.getElementById(`flag_chk_${qid}`).checked) flagCount++;
        });

        const unansCount = totalQuestions - ansCount;

        document.getElementById('modalAnsCount').textContent = ansCount;
        document.getElementById('modalFlagCount').textContent = flagCount;
        document.getElementById('modalUnansCount').textContent = unansCount;

        const warnAlert = document.getElementById('unansweredWarningAlert');
        if (unansCount > 0) {
            warnAlert.classList.remove('d-none');
            document.getElementById('warnUnansNum').textContent = unansCount;
        } else {
            warnAlert.classList.add('d-none');
        }

        submitModal.show();
    }

    btnSubmitModalTrigger.addEventListener('click', openSubmitModal);
    btnFinishEarly.addEventListener('click', openSubmitModal);

    btnConfirmSubmit.addEventListener('click', function() {
        clearInterval(timerInterval);
        quizForm.submit();
    });

    // Initialize UI
    updateUI();

    // ================= STRICT EXAM RULES =================

    // 1. Fullscreen enforcement
    const enterFullscreen = () => {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(err => {
                console.log(`Error attempting to enable fullscreen: ${err.message}`);
            });
        }
    };

    // Suggest fullscreen on click anywhere in the container initially
    document.getElementById('quizAppContainer').addEventListener('click', enterFullscreen, { once: true });

    // 2. Anti-tab / Focus loss detection
    let warningCount = 0;
    const maxWarnings = 3;

    document.addEventListener("visibilitychange", function() {
        if (document.hidden) {
            handleTabSwitchWarning();
        }
    });

    window.addEventListener("blur", function() {
        handleTabSwitchWarning();
    });

    function handleTabSwitchWarning() {
        // Prevent multiple simultaneous triggers
        if(window.isWarningActive) return;
        window.isWarningActive = true;

        warningCount++;

        if (warningCount >= maxWarnings) {
            Swal.fire({
                title: 'Exam Terminated',
                text: 'You have violated the exam rules multiple times by navigating away from the test window. Your exam is now being submitted automatically.',
                icon: 'error',
                showConfirmButton: false,
                allowOutsideClick: false,
                timer: 4000
            }).then(() => {
                clearInterval(timerInterval);
                quizForm.submit();
            });
        } else {
            Swal.fire({
                title: 'Warning: Exam Rule Violation',
                text: `You left the exam window or switched tabs. Warning ${warningCount} of ${maxWarnings}. Exam will be terminated if you continue.`,
                icon: 'warning',
                confirmButtonText: 'I understand',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(() => {
                window.isWarningActive = false;
            });
        }
    }

    // Protect against right click to copy
    document.addEventListener('contextmenu', event => event.preventDefault());

    // Protect against copy paste shortcuts
    document.addEventListener('keydown', function (e) {
        if (e.ctrlKey && (e.key === 'c' || e.key === 'v' || e.key === 'x')) {
            e.preventDefault();
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Copy/paste is disabled during exams.',
                showConfirmButton: false,
                timer: 3000
            });
        }
    });

});
</script>
@endpush
