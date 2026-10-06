<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MockTest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MockTestController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $tests = MockTest::with('student.user')->select('mock_tests.*');

            return DataTables::of($tests)
                ->addColumn('student', function ($test) {
                    return $test->student && $test->student->user ? e($test->student->user->name) : 'N/A';
                })
                ->addColumn('score_formatted', function ($test) {
                    return '<strong>' . $test->score . '</strong> / ' . $test->total_questions . ' (' . number_format($test->percentage, 1) . '%)';
                })
                ->addColumn('result_badge', function ($test) {
                    return $test->result === 'pass'
                        ? '<span class="badge bg-success px-3 py-1 rounded-pill">PASS</span>'
                        : '<span class="badge bg-danger px-3 py-1 rounded-pill">FAIL</span>';
                })
                ->addColumn('duration', function ($test) {
                    $minutes = floor($test->time_taken_seconds / 60);
                    $seconds = $test->time_taken_seconds % 60;
                    return sprintf('%02dm %02ds', $minutes, $seconds);
                })
                ->addColumn('date', function ($test) {
                    return $test->created_at->format('d M Y, h:i A');
                })
                ->addColumn('actions', function ($test) {
                    return '<a href="' . route('admin.mock-tests.show', $test->id) . '" class="btn btn-sm btn-outline-info"><i class="fa-solid fa-eye me-1"></i> Review</a>';
                })
                ->rawColumns(['score_formatted', 'result_badge', 'actions'])
                ->make(true);
        }

        return view('admin.mock_tests.index');
    }

    public function show(MockTest $mockTest)
    {
        $mockTest->load('student.user');
        return view('admin.mock_tests.show', compact('mockTest'));
    }
}
