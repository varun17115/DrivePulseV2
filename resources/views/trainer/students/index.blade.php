@extends('layouts.admin')

@section('title', 'My Students')
@section('page_title', 'Trained Students Directory')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">My Students</li>
@endsection

@section('content')
<div class="row g-4">
    @forelse($students as $student)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-lg bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold fs-4 me-3" style="width: 54px; height: 54px;">
                            {{ substr($student->user->name, 0, 1) }}
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $student->user->name }}</h6>
                            <div class="small text-muted">{{ $student->admission_number }}</div>
                            <span class="badge bg-light text-dark border mt-1">{{ strtoupper($student->license_type ?? 'LMV') }}</span>
                        </div>
                    </div>

                    <hr class="text-muted opacity-25 my-3">

                    <div class="small mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted"><i class="fa-solid fa-phone me-1"></i> Phone:</span>
                            <span class="fw-semibold">{{ $student->user->phone ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted"><i class="fa-solid fa-graduation-cap me-1"></i> Course Status:</span>
                            <span class="badge {{ $student->course_status === 'completed' ? 'bg-success' : 'bg-primary' }}">
                                {{ ucfirst($student->course_status) }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted"><i class="fa-solid fa-road me-1"></i> Lessons with you:</span>
                            <span class="fw-bold text-primary">{{ $student->bookings->count() }}</span>
                        </div>
                    </div>

                    <a href="{{ route('trainer.students.show', $student->id) }}" class="btn btn-sm btn-outline-primary w-100 rounded-pill">
                        <i class="fa-solid fa-user-gear me-1"></i> View Performance Log
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm py-5 text-center text-muted">
                <i class="fa-solid fa-user-slash fa-3x mb-3 text-secondary"></i>
                <h5>No Students Found</h5>
                <p class="mb-0">You have not conducted any booked driving sessions with students yet.</p>
            </div>
        </div>
    @endforelse
</div>

@if($students->hasPages())
    <div class="mt-4">
        {{ $students->links() }}
    </div>
@endif
@endsection
