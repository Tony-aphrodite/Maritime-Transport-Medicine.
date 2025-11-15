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

Route::get('/login', [App\Http\Controllers\LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [App\Http\Controllers\LoginController::class, 'processLogin'])->name('login.submit');

// Login help page
Route::get('/login-help', function() {
    return view('login-help');
});

// Browser security help
Route::get('/browser-help', function() {
    return view('browser-help');
});

Route::get('/registro', [App\Http\Controllers\RegistrationController::class, 'showRegistrationForm'])->name('registro');
Route::post('/registro', [App\Http\Controllers\RegistrationController::class, 'processRegistration'])->name('registro.submit');

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
    // Redirect /admin to main login
    Route::get('/', function() {
        return redirect()->route('login');
    });
    
    // Admin logout
    Route::get('/logout', [App\Http\Controllers\LoginController::class, 'logout'])->name('admin.logout');
    
    // Protected Admin Routes
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/audit-logs', [App\Http\Controllers\AdminController::class, 'auditLogs'])->name('admin.audit.logs');
    Route::get('/audit-logs/export', [App\Http\Controllers\AdminController::class, 'exportAuditLogs'])->name('admin.audit.export');
    
    // API Routes for admin
    Route::prefix('api')->group(function () {
        Route::get('/dashboard-stats', [App\Http\Controllers\AdminController::class, 'getDashboardStats'])->name('admin.api.dashboard.stats');
        Route::get('/audit-logs-data', [App\Http\Controllers\AdminController::class, 'getAuditLogsData'])->name('admin.api.audit.data');
        Route::get('/audit-log/{id}', [App\Http\Controllers\AdminController::class, 'getAuditLogDetails'])->name('admin.api.audit.details');
    });
    
    // Test route for creating sample audit logs (FOR TESTING ONLY)
    Route::get('/create-test-data', function() {
        try {
            $result = App\Models\AuditLog::createTestData();
            return response()->json([
                'success' => $result,
                'message' => $result ? 'Test audit log data created successfully!' : 'Failed to create test data (database may be unavailable)'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    });
    
    // Test login credentials (FOR TESTING ONLY - REMOVE IN PRODUCTION)  
    Route::get('/test-credentials', function() {
        $adminCredentials = [
            'email' => 'AdminJuan@gmail.com',
            'password' => 'johnson@suceess!'
        ];
        
        return response()->json([
            'expected_credentials' => $adminCredentials,
            'message' => 'Use these credentials to login'
        ]);
    });
    
    // Test login page (FOR TESTING ONLY)
    Route::get('/test-login', function() {
        return view('admin.test-login');
    });
    
    // Admin status check (FOR TESTING ONLY)
    Route::get('/admin-status', function() {
        $isLoggedIn = Session::get('admin_logged_in', false);
        $adminEmail = Session::get('admin_email', 'Not logged in');
        
        return response()->json([
            'admin_logged_in' => $isLoggedIn,
            'admin_email' => $adminEmail,
            'session_id' => session()->getId(),
            'expected_credentials' => [
                'email' => 'AdminJuan@gmail.com',
                'password' => 'johnson@suceess!'
            ],
            'login_url' => url('/admin/login'),
            'dashboard_url' => url('/admin/dashboard')
        ]);
    });
});
