<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Trainer\DashboardController;
use App\Http\Controllers\Trainer\BookingController;
use App\Http\Controllers\Trainer\AttendanceController;
use App\Http\Controllers\Trainer\ProgressController;
use App\Http\Controllers\Trainer\StudentController;
use App\Http\Controllers\Trainer\VehicleController;

Route::middleware(['auth', 'role:trainer'])->prefix('trainer')->name('trainer.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // My Schedule (Bookings)
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.status.update');

    // Attendance
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');

    // Evaluations
    Route::get('/progress', [ProgressController::class, 'index'])->name('progress.index');
    Route::post('/progress', [ProgressController::class, 'store'])->name('progress.store');

    // My Students
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');

    // Assigned Vehicles
    Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
});
