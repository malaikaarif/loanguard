<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoanApplicationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('applicant.dashboard');
})->middleware(['auth'])->name('dashboard');

// Applicant routes
Route::middleware(['auth'])->group(function () {
    Route::get('/applicant/dashboard', [App\Http\Controllers\ApplicantController::class, 'index'])
        ->name('applicant.dashboard');

    Route::resource('applications', LoanApplicationController::class)
        ->only(['index', 'create', 'store', 'show', 'destroy']);
});

// Admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'index'])
        ->name('dashboard');
    Route::get('/applications', [LoanApplicationController::class, 'adminIndex'])
        ->name('applications.index');
    Route::patch('/applications/{loanApplication}/status', [LoanApplicationController::class, 'updateStatus'])
        ->name('applications.status');
});

require __DIR__.'/auth.php';