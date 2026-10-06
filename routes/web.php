<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    // Dynamic Role-based Dashboard Redirection
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('trainer')) {
            return redirect()->route('trainer.dashboard');
        } elseif ($user->hasRole('student')) {
            return redirect()->route('student.dashboard');
        }

        // Fallback
        abort(403, 'Unauthorized action.');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markRead');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
});

require __DIR__.'/auth.php';

// Load role-specific routes
require __DIR__.'/admin.php';
require __DIR__.'/trainer.php';
require __DIR__.'/student.php';

Route::get('verify-certificate/{hash}', function ($hash) {
    $certificate = \App\Models\Certificate::where('qr_code_hash', $hash)->firstOrFail();
    return view('certificate.verify', compact('certificate'));
})->name('verify-certificate');
