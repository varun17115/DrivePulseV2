@extends('layouts.admin')

@section('title', 'Book a Lesson')
@section('page_title', 'Book New Driving Lesson')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('student.bookings.index') }}">My Bookings</a></li>
    <li class="breadcrumb-item active" aria-current="page">Book</li>
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('student.bookings.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold"><i class="fa-solid fa-user-tie text-primary me-1"></i>Instructor / Trainer</label>
                    <select name="trainer_id" class="form-select @error('trainer_id') is-invalid @enderror" required>
                        <option value="">-- Choose Instructor --</option>
                        @forelse($trainers as $trainer)
                            <option value="{{ $trainer->id }}" {{ old('trainer_id') == $trainer->id ? 'selected' : '' }}>
                                {{ $trainer->user->name ?? 'Trainer' }} — {{ $trainer->specialization ?? 'General Driving' }}
                            </option>
                        @empty
                            <option value="" disabled>No active instructors currently available</option>
                        @endforelse
                    </select>
                    @error('trainer_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold"><i class="fa-solid fa-car text-primary me-1"></i>Training Vehicle</label>
                    <select name="vehicle_id" class="form-select @error('vehicle_id') is-invalid @enderror" required>
                        <option value="">-- Choose Vehicle --</option>
                        @forelse($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                {{ $vehicle->make }} {{ $vehicle->model }} ({{ ucfirst($vehicle->transmission_type) }}) • {{ $vehicle->registration_number }}
                            </option>
                        @empty
                            <option value="" disabled>No vehicles currently available</option>
                        @endforelse
                    </select>
                    @error('vehicle_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold"><i class="fa-regular fa-calendar-days text-primary me-1"></i>Lesson Date</label>
                    <input type="date" name="booking_date" value="{{ old('booking_date') }}" class="form-control @error('booking_date') is-invalid @enderror" min="{{ date('Y-m-d') }}" required>
                    @error('booking_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold"><i class="fa-regular fa-clock text-primary me-1"></i>Start Time</label>
                    <input type="time" name="start_time" value="{{ old('start_time', '09:00') }}" class="form-control @error('start_time') is-invalid @enderror" required>
                    @error('start_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold"><i class="fa-regular fa-clock text-primary me-1"></i>End Time</label>
                    <input type="time" name="end_time" value="{{ old('end_time', '10:00') }}" class="form-control @error('end_time') is-invalid @enderror" required>
                    @error('end_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label fw-semibold"><i class="fa-solid fa-graduation-cap text-primary me-1"></i>Lesson Type</label>
                    <select name="lesson_type" class="form-select @error('lesson_type') is-invalid @enderror" required>
                        <option value="practical" {{ old('lesson_type') == 'practical' ? 'selected' : '' }}>Practical On-Road Driving</option>
                        <option value="theory" {{ old('lesson_type') == 'theory' ? 'selected' : '' }}>Theory / Classroom Session</option>
                        <option value="refresher" {{ old('lesson_type') == 'refresher' ? 'selected' : '' }}>Refresher Course</option>
                    </select>
                    @error('lesson_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="text-end">
                <a href="{{ route('student.bookings.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Submit Booking</button>
            </div>
        </form>
    </div>
</div>
@endsection
