<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoanApplicationController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\RepaymentController;

Route::get('/', function () {
    return view('welcome');
});

// Applicant feedback
Route::middleware('auth')->group(function () {
    Route::get('/feedback', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
});

// Admin view feedbacks
Route::middleware(['auth', 'admin'])->group(function () {
    // ... your existing admin routes ...
    Route::get('/admin/feedbacks', [FeedbackController::class, 'index'])->name('admin.feedbacks');
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
    ->only(['index', 'create', 'store', 'show', 'destroy'])
    ->parameters(['applications' => 'loanApplication']);

    Route::get('applications/{loanApplication}/pdf', [LoanApplicationController::class, 'downloadPdf'])
        ->name('applications.pdf');
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


// Applicant repayments
Route::middleware('auth')->group(function () {
    Route::get('/repayments/{loanApplication}', [RepaymentController::class, 'show'])->name('repayments.show');
    Route::post('/repayments/{loanApplication}/pay/{repayment}', [RepaymentController::class, 'pay'])->name('repayments.pay');
});

// Admin repayments
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/repayments', [RepaymentController::class, 'index'])->name('admin.repayments');
});

require __DIR__.'/auth.php';