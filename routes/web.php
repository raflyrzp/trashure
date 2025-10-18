<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    Route::delete('reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');

    // Admin-only: update status
    Route::middleware('admin')->group(function () {
        Route::post('reports/{report}/status', [ReportController::class, 'updateStatus'])->name('reports.updateStatus');
    });

    // Leaderboard
    Route::get('leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');

    // Education
    Route::get('education', [EducationController::class, 'index'])->name('education.index');
    Route::get('education/{slug}', [EducationController::class, 'show'])->name('education.show');

    // Profile
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
});
