@extends('layouts.admin')

@section('title', 'Edit Question')
@section('page_title', 'Edit Question')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.mock-questions.index') }}">Question Bank</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit #{{ $mockQuestion->id }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent py-3">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-pen-to-square text-primary me-2"></i>Edit Question #{{ $mockQuestion->id }}</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.mock-questions.update', $mockQuestion->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4 mb-4">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                            <input type="text" name="category" class="form-control" placeholder="e.g. Road Signs, General, Emergencies" value="{{ old('category', $mockQuestion->category) }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Question Text <span class="text-danger">*</span></label>
                            <textarea name="question" class="form-control" rows="3" required>{{ old('question', $mockQuestion->question) }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-primary">Option A <span class="text-danger">*</span></label>
                            <input type="text" name="option_a" class="form-control" value="{{ old('option_a', $mockQuestion->option_a) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-primary">Option B <span class="text-danger">*</span></label>
                            <input type="text" name="option_b" class="form-control" value="{{ old('option_b', $mockQuestion->option_b) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-primary">Option C <span class="text-danger">*</span></label>
                            <input type="text" name="option_c" class="form-control" value="{{ old('option_c', $mockQuestion->option_c) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-primary">Option D <span class="text-danger">*</span></label>
                            <input type="text" name="option_d" class="form-control" value="{{ old('option_d', $mockQuestion->option_d) }}" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold text-success"><i class="fa-solid fa-check-circle me-1"></i> Correct Option <span class="text-danger">*</span></label>
                            <select name="correct_option" class="form-select border-success" required>
                                <option value="A" {{ old('correct_option', $mockQuestion->correct_option) == 'A' ? 'selected' : '' }}>Option A</option>
                                <option value="B" {{ old('correct_option', $mockQuestion->correct_option) == 'B' ? 'selected' : '' }}>Option B</option>
                                <option value="C" {{ old('correct_option', $mockQuestion->correct_option) == 'C' ? 'selected' : '' }}>Option C</option>
                                <option value="D" {{ old('correct_option', $mockQuestion->correct_option) == 'D' ? 'selected' : '' }}>Option D</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Explanation (shown after test)</label>
                            <textarea name="explanation" class="form-control" rows="2">{{ old('explanation', $mockQuestion->explanation) }}</textarea>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" {{ old('is_active', $mockQuestion->is_active) ? 'checked' : '' }} value="1">
                                <label class="form-check-label fw-semibold" for="is_active">Is Active (Appears in mock tests)</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.mock-questions.index') }}" class="btn btn-light rounded-pill px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Update Question</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
