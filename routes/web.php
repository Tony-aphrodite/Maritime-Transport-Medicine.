<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/hello', function () {
    return view('hello');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/registro', function () {
    return view('registro');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/password/reset', function () {
    return view('password-reset');
})->name('password.request');

Route::post('/password/email', function () {
    // Here you would handle the password reset email logic
    return redirect()->back()->with('status', 'Se ha enviado un enlace de recuperación a tu correo electrónico.');
})->name('password.email');

// CURP Validation Routes
Route::get('/curp/validate', [App\Http\Controllers\CurpController::class, 'showValidationForm'])->name('curp.validate');
Route::post('/curp/validate', [App\Http\Controllers\CurpController::class, 'validateCurp'])->name('curp.validate.submit');
Route::post('/curp/validate-format', [App\Http\Controllers\CurpController::class, 'validateFormat'])->name('curp.validate.format');

// Face Verification Routes
Route::get('/face-verification', [App\Http\Controllers\FaceVerificationController::class, 'index'])->name('face.verification');
Route::post('/face-verification/compare', [App\Http\Controllers\FaceVerificationController::class, 'compareFaces'])->name('face.verification.compare');
Route::get('/face-verification/status', [App\Http\Controllers\FaceVerificationController::class, 'getVerificationStatus'])->name('face.verification.status');

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/audit-logs', [App\Http\Controllers\AdminController::class, 'auditLogs'])->name('admin.audit.logs');
    Route::get('/audit-logs/export', [App\Http\Controllers\AdminController::class, 'exportAuditLogs'])->name('admin.audit.export');
    
    // API Routes for admin
    Route::prefix('api')->group(function () {
        Route::get('/dashboard-stats', [App\Http\Controllers\AdminController::class, 'getDashboardStats'])->name('admin.api.dashboard.stats');
        Route::get('/audit-logs-data', [App\Http\Controllers\AdminController::class, 'getAuditLogsData'])->name('admin.api.audit.data');
    });
});
