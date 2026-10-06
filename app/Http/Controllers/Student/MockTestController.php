<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\MockTest;
use App\Models\MockTestQuestion;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MockTestController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;
        if (!$student) {
            abort(403, 'Student profile not found.');
        }

        $tests = MockTest::where('student_id', $student->id)
            ->latest()
            ->paginate(10);

        $totalTests = MockTest::where('student_id', $student->id)->count();
        $passedTests = MockTest::where('student_id', $student->id)->where('result', 'pass')->count();
        $avgScore = $totalTests > 0
            ? round(MockTest::where('student_id', $student->id)->avg('percentage'), 1)
            : 0;
        $bestScore = $totalTests > 0
            ? round(MockTest::where('student_id', $student->id)->max('percentage'), 1)
            : 0;

        // Fetch question counts per category for UI badges
        $categories = MockTestQuestion::where('is_active', true)
            ->selectRaw('category, count(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        return view('student.mock_tests.index', compact('tests', 'totalTests', 'passedTests', 'avgScore', 'bestScore', 'categories'));
    }

    public function start(Request $request)
    {
        $student = Auth::user()->student;
        if (!$student) {
            abort(403, 'Student profile not found.');
        }

        $mode = $request->input('mode', 'full'); // full, quick, practice
        $category = $request->input('category');

        $query = MockTestQuestion::where('is_active', true);

        if ($category && in_array($category, ['Road Signs', 'Traffic Rules', 'Vehicle Controls', 'Emergencies', 'General'])) {
            $query->where('category', $category);
        }

        $limit = 20;
        $timerMinutes = 20;

        if ($mode === 'quick') {
            $limit = 10;
            $timerMinutes = 10;
        } elseif ($mode === 'practice') {
            $limit = 15;
            $timerMinutes = 15;
        }

        $questions = $query->inRandomOrder()->limit($limit)->get();

        if ($questions->isEmpty()) {
            return redirect()->route('student.mock-tests.index')
                ->with('error', 'No active questions available for the selected criteria.');
        }

        return view('student.mock_tests.quiz', compact('questions', 'category', 'mode', 'timerMinutes'));
    }

    public function submit(Request $request)
    {
        $student = Auth::user()->student;
        if (!$student) {
            abort(403, 'Student profile not found.');
        }

        $validated = $request->validate([
            'answers' => 'nullable|array',
            'flagged' => 'nullable|array',
            'time_taken_seconds' => 'required|integer|min:0',
        ]);

        $submittedAnswers = $validated['answers'] ?? [];
        $flaggedQuestions = $validated['flagged'] ?? [];
        $timeTaken = $validated['time_taken_seconds'];

        $allQuestionIds = $request->input('question_ids', []);
        if (is_string($allQuestionIds)) {
            $allQuestionIds = explode(',', $allQuestionIds);
        }
        $questionIds = array_keys($submittedAnswers);
        $allQuestionIds = array_unique(array_merge($questionIds, $allQuestionIds));

        $questions = MockTestQuestion::whereIn('id', $allQuestionIds)->get();

        $totalQuestions = $questions->count();
        if ($totalQuestions === 0) {
            return redirect()->route('student.mock-tests.index')->with('error', 'Invalid test submission.');
        }

        $score = 0;
        $breakdown = [];
        $categoryScores = [];

        foreach ($questions as $question) {
            $selectedOption = $submittedAnswers[$question->id] ?? null;
            $isCorrect = $selectedOption === $question->correct_option;
            $isFlagged = !empty($flaggedQuestions[$question->id]);

            if ($isCorrect) {
                $score++;
            }

            $cat = $question->category ?? 'General';
            if (!isset($categoryScores[$cat])) {
                $categoryScores[$cat] = ['total' => 0, 'correct' => 0];
            }
            $categoryScores[$cat]['total']++;
            if ($isCorrect) {
                $categoryScores[$cat]['correct']++;
            }

            $breakdown[] = [
                'question_id' => $question->id,
                'question' => $question->question,
                'option_a' => $question->option_a,
                'option_b' => $question->option_b,
                'option_c' => $question->option_c,
                'option_d' => $question->option_d,
                'selected_option' => $selectedOption,
                'correct_option' => $question->correct_option,
                'explanation' => $question->explanation,
                'category' => $question->category,
                'image' => $question->image,
                'is_flagged' => $isFlagged,
            ];
        }

        $percentage = round(($score / $totalQuestions) * 100, 2);
        $result = $percentage >= 80.0 ? 'pass' : 'fail';

        $mockTest = MockTest::create([
            'student_id' => $student->id,
            'test_code' => 'MOCK-' . strtoupper(Str::random(8)),
            'total_questions' => $totalQuestions,
            'score' => $score,
            'percentage' => $percentage,
            'result' => $result,
            'time_taken_seconds' => $timeTaken,
            'question_answers' => $breakdown,
            'taken_at' => now(),
        ]);

        if ($student->user) {
            NotificationService::send(
                $student->user,
                'Mock Test Result: ' . strtoupper($result),
                'You scored ' . $score . '/' . $totalQuestions . ' (' . $percentage . '%) in your mock theory test.',
                $result === 'pass' ? 'success' : 'warning',
                route('student.mock-tests.show', $mockTest->id)
            );
        }

        return redirect()->route('student.mock-tests.show', $mockTest->id)
            ->with('success', 'Mock test completed successfully!');
    }

    public function show(MockTest $mockTest)
    {
        $student = Auth::user()->student;
        if (!$student || $mockTest->student_id !== $student->id) {
            abort(403, 'Unauthorized access to test result.');
        }

        // Calculate Category Breakdown stats from stored breakdown
        $categoryBreakdown = [];
        if (!empty($mockTest->question_answers) && is_array($mockTest->question_answers)) {
            foreach ($mockTest->question_answers as $item) {
                $cat = $item['category'] ?? 'General';
                if (!isset($categoryBreakdown[$cat])) {
                    $categoryBreakdown[$cat] = ['total' => 0, 'correct' => 0];
                }
                $categoryBreakdown[$cat]['total']++;
                if (($item['selected_option'] ?? '') === ($item['correct_option'] ?? '')) {
                    $categoryBreakdown[$cat]['correct']++;
                }
            }
        }

        return view('student.mock_tests.show', compact('mockTest', 'categoryBreakdown'));
    }
}

