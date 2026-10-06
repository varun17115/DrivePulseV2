<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MockTestQuestion;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MockTestQuestionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $questions = MockTestQuestion::query();

            return DataTables::of($questions)
                ->addColumn('category_badge', function ($q) {
                    return '<span class="badge bg-primary-subtle text-primary border border-primary-subtle">' . e($q->category) . '</span>';
                })
                ->addColumn('question_snippet', function ($q) {
                    return '<div class="fw-semibold text-dark text-wrap">' . e(mb_strimwidth($q->question, 0, 80, '...')) . '</div>';
                })
                ->addColumn('correct', function ($q) {
                    $optKey = 'option_' . strtolower($q->correct_option);
                    return '<span class="badge bg-success">Option ' . $q->correct_option . '</span> <small class="text-muted">' . e(mb_strimwidth($q->$optKey, 0, 30, '...')) . '</small>';
                })
                ->addColumn('status', function ($q) {
                    return $q->is_active
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-secondary">Inactive</span>';
                })
                ->addColumn('actions', function ($q) {
                    $editUrl = route('admin.mock-questions.edit', $q->id);
                    $deleteUrl = route('admin.mock-questions.destroy', $q->id);

                    return '
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="' . $editUrl . '" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen-to-square"></i></a>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-url="' . $deleteUrl . '" data-name="Question #' . $q->id . '">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['category_badge', 'question_snippet', 'correct', 'status', 'actions'])
                ->make(true);
        }

        return view('admin.mock_questions.index');
    }

    public function create()
    {
        return view('admin.mock_questions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:100',
            'question' => 'required|string',
            'option_a' => 'required|string|max:255',
            'option_b' => 'required|string|max:255',
            'option_c' => 'required|string|max:255',
            'option_d' => 'required|string|max:255',
            'correct_option' => 'required|in:A,B,C,D',
            'explanation' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        MockTestQuestion::create($validated);

        return redirect()->route('admin.mock-questions.index')->with('success', 'Question created successfully!');
    }

    public function edit(MockTestQuestion $mockQuestion)
    {
        return view('admin.mock_questions.edit', compact('mockQuestion'));
    }

    public function update(Request $request, MockTestQuestion $mockQuestion)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:100',
            'question' => 'required|string',
            'option_a' => 'required|string|max:255',
            'option_b' => 'required|string|max:255',
            'option_c' => 'required|string|max:255',
            'option_d' => 'required|string|max:255',
            'correct_option' => 'required|in:A,B,C,D',
            'explanation' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $mockQuestion->update($validated);

        return redirect()->route('admin.mock-questions.index')->with('success', 'Question updated successfully!');
    }

    public function destroy(MockTestQuestion $mockQuestion)
    {
        $mockQuestion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Question deleted successfully!'
        ]);
    }
}
