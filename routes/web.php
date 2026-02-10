<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Auth\PasswordResetController;

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

// TEMPORARY: Clear cache route - REMOVE AFTER USE
Route::get('/clear-cache', function () {
    \Artisan::call('config:clear');
    \Artisan::call('route:clear');
    \Artisan::call('view:clear');
    \Artisan::call('cache:clear');
    return 'Cache cleared! <a href="/seed-zonas-horarias">Now click here to seed zonas horarias</a>';
});

Route::get('/', function () {
    return view('landing');
})->name('home');


// ========================================
// Auth API Routes (for landing page AJAX)
// ========================================
Route::prefix('api/auth')->group(function () {
    Route::post('/login', [AuthApiController::class, 'login'])->name('api.auth.login');
    Route::post('/register', [AuthApiController::class, 'register'])->name('api.auth.register');
    Route::post('/resend-verification', [AuthApiController::class, 'resendVerification'])->name('api.auth.resend');
    Route::post('/logout', [AuthApiController::class, 'logout'])->name('api.auth.logout');
});

// ========================================
// Authentication Routes
// ========================================

// Login Routes - Redirect to home page (login is handled on landing page)
Route::get('/login', function () {
    return redirect('/');
})->name('login');

// Keep POST routes for form submissions
Route::post('/login', [App\Http\Controllers\LoginController::class, 'processLogin'])->name('login.submit');
Route::post('/logout', [App\Http\Controllers\LoginController::class, 'logout'])->name('logout');

// Registration Routes (New Flow - Step 1)
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Email Verification Routes (Step 2)
Route::get('/email/verify', [VerificationController::class, 'notice'])
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verifyWithoutAuth'])
    ->middleware(['signed'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [VerificationController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.resend');

// Profile Completion Routes (Legacy - redirect to profile page)
Route::get('/complete-profile', function () {
    return redirect()->route('profile.show');
})->middleware(['auth', 'verified'])->name('profile.complete');

Route::post('/complete-profile', function () {
    return redirect()->route('profile.show');
})->middleware(['auth', 'verified'])->name('profile.complete.submit');

// Dashboard
Route::get('/dashboard', function () {
    return view('user-dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// User Dashboard (alias for dashboard)
Route::get('/user-dashboard', function () {
    return view('user-dashboard');
})->middleware(['auth'])->name('user.dashboard');

// ========================================
// User Profile Routes
// ========================================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [App\Http\Controllers\UserProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [App\Http\Controllers\UserProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo', [App\Http\Controllers\UserProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::post('/profile/password', [App\Http\Controllers\UserProfileController::class, 'updatePassword'])->name('profile.password.update');
});

// ========================================
// Legacy Registration Route (redirect to new flow)
// ========================================
Route::get('/registro', function() {
    return redirect()->route('register');
})->name('registro');

// ========================================
// Help Pages
// ========================================

// Login help page
Route::get('/login-help', function() {
    return view('login-help');
});

// Browser security help
Route::get('/browser-help', function() {
    return view('browser-help');
});

// ========================================
// Password Reset Routes
// ========================================
Route::get('/password/reset', [PasswordResetController::class, 'showRequestForm'])->name('password.request');
Route::post('/password/email', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [PasswordResetController::class, 'reset'])->name('password.update');

// ========================================
// CURP Validation Routes
// ========================================
Route::get('/curp/validate', [App\Http\Controllers\CurpController::class, 'showValidationForm'])->name('curp.validate');
Route::post('/curp/validate', [App\Http\Controllers\CurpController::class, 'validateCurp'])->name('curp.validate.submit');
Route::post('/curp/validate-format', [App\Http\Controllers\CurpController::class, 'validateFormat'])->name('curp.validate.format');

// ========================================
// Face Verification Routes
// ========================================
Route::get('/face-verification', [App\Http\Controllers\FaceVerificationController::class, 'index'])->name('face.verification');
Route::post('/face-verification/compare', [App\Http\Controllers\FaceVerificationController::class, 'compareFaces'])->name('face.verification.compare');
Route::get('/face-verification/status', [App\Http\Controllers\FaceVerificationController::class, 'getVerificationStatus'])->name('face.verification.status');

// ========================================
// Admin Routes
// ========================================
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
    Route::get('/users', function() { return view('admin.users'); })->name('admin.users');
    Route::get('/settings', function() { return view('admin.settings'); })->name('admin.settings');

    // Appointment Management Routes
    Route::get('/appointments', [App\Http\Controllers\AdminAppointmentController::class, 'index'])->name('admin.appointments.index');
    Route::get('/appointments/{id}', [App\Http\Controllers\AdminAppointmentController::class, 'show'])->name('admin.appointments.show');
    Route::post('/appointments/{id}/status', [App\Http\Controllers\AdminAppointmentController::class, 'updateStatus'])->name('admin.appointments.status');

    // API Routes for admin
    Route::prefix('api')->group(function () {
        Route::get('/dashboard-stats', [App\Http\Controllers\AdminController::class, 'getDashboardStats'])->name('admin.api.dashboard.stats');
        Route::get('/audit-logs-data', [App\Http\Controllers\AdminController::class, 'getAuditLogsData'])->name('admin.api.audit.data');
        Route::get('/audit-log/{id}', [App\Http\Controllers\AdminController::class, 'getAuditLogDetails'])->name('admin.api.audit.details');
        Route::get('/recent-events', [App\Http\Controllers\AdminController::class, 'getRecentAuditEvents'])->name('admin.api.recent.events');
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

// ========================================
// Parental Consent Routes
// ========================================
Route::prefix('parental-consent')->group(function () {
    Route::get('/approve/{token}', [App\Http\Controllers\ParentalConsentController::class, 'showConsentForm'])->name('parental.consent.form');
    Route::post('/approve/{token}', [App\Http\Controllers\ParentalConsentController::class, 'processConsent'])->name('parental.consent.process');
    Route::get('/status/{token}', [App\Http\Controllers\ParentalConsentController::class, 'checkStatus'])->name('parental.consent.status');
});

// ========================================
// Appointment Booking Routes
// ========================================
Route::middleware(['auth', 'verified'])->prefix('appointments')->name('appointments.')->group(function () {
    // Step 1 - Date and Time Selection
    Route::get('/step1', [App\Http\Controllers\AppointmentController::class, 'step1'])->name('step1');
    Route::post('/step1', [App\Http\Controllers\AppointmentController::class, 'processStep1'])->name('step1.process');

    // Slot Hold Management (AJAX)
    Route::post('/hold-slot', [App\Http\Controllers\AppointmentController::class, 'holdSlot'])->name('hold.slot');
    Route::post('/release-slot', [App\Http\Controllers\AppointmentController::class, 'releaseSlot'])->name('release.slot');
    Route::get('/check-hold', [App\Http\Controllers\AppointmentController::class, 'checkHoldStatus'])->name('check.hold');

    // Step 2 - File Upload
    Route::get('/step2', [App\Http\Controllers\AppointmentController::class, 'step2'])->name('step2');
    Route::post('/step2', [App\Http\Controllers\AppointmentController::class, 'processStep2'])->name('step2.process');
    Route::post('/upload', [App\Http\Controllers\AppointmentController::class, 'uploadFile'])->name('upload');
    Route::delete('/document/{id}', [App\Http\Controllers\AppointmentController::class, 'deleteFile'])->name('document.delete');

    // Step 3 - Medical Declaration
    Route::get('/step3', [App\Http\Controllers\AppointmentController::class, 'step3'])->name('step3');
    Route::post('/step3', [App\Http\Controllers\AppointmentController::class, 'processStep3'])->name('step3.process');

    // Step 4 - Confirmation
    Route::get('/step4', [App\Http\Controllers\AppointmentController::class, 'step4'])->name('step4');
    Route::post('/step4', [App\Http\Controllers\AppointmentController::class, 'processStep4'])->name('step4.process');

    // Step 5 - Payment
    Route::get('/step5', [App\Http\Controllers\AppointmentController::class, 'step5'])->name('step5');
    Route::post('/payment', [App\Http\Controllers\AppointmentController::class, 'processPayment'])->name('payment.process');

    // Payment return routes
    Route::get('/payment/success', [App\Http\Controllers\MercadoPagoController::class, 'success'])->name('payment.success');
    Route::get('/payment/failure', [App\Http\Controllers\MercadoPagoController::class, 'failure'])->name('payment.failure');
    Route::get('/payment/pending', [App\Http\Controllers\MercadoPagoController::class, 'pending'])->name('payment.pending');

    // Success Page / Confirmation
    Route::get('/success/{id}', [App\Http\Controllers\AppointmentController::class, 'success'])->name('success');
    Route::get('/confirmation/{id}', [App\Http\Controllers\AppointmentController::class, 'success'])->name('confirmation');
});

// ========================================
// Mercado Pago Routes
// ========================================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/mercadopago/create-preference', [App\Http\Controllers\MercadoPagoController::class, 'createPreference'])->name('mercadopago.create-preference');
});

// Mercado Pago Webhook (no auth required - called by MercadoPago servers)
Route::post('/mercadopago/webhook', [App\Http\Controllers\MercadoPagoController::class, 'webhook'])->name('mercadopago.webhook');

// TEMPORARY: Create storage symlink - REMOVE AFTER USE
Route::get('/create-storage-link', function () {
    $target = storage_path('app/public');
    $link = public_path('storage');

    if (file_exists($link)) {
        return response()->json([
            'success' => true,
            'message' => 'Storage link already exists',
            'link' => $link,
            'target' => $target
        ]);
    }

    try {
        symlink($target, $link);
        return response()->json([
            'success' => true,
            'message' => 'Storage link created successfully',
            'link' => $link,
            'target' => $target
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to create symlink: ' . $e->getMessage(),
            'link' => $link,
            'target' => $target
        ]);
    }
});

// Serve profile photos directly (workaround for symlink issues on shared hosting)
Route::get('/storage/profile-photos/{filename}', function ($filename) {
    $path = storage_path('app/public/profile-photos/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    $mimeType = mime_content_type($path);
    return response()->file($path, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=31536000',
    ]);
})->where('filename', '.*');

// TEMPORARY: Debug storage - REMOVE AFTER USE
Route::get('/debug-storage', function () {
    $storagePath = storage_path('app/public/profile-photos');
    $publicPath = public_path('storage/profile-photos');

    $storageFiles = [];
    $publicFiles = [];

    if (is_dir($storagePath)) {
        $storageFiles = array_diff(scandir($storagePath), ['.', '..']);
    }

    if (is_dir($publicPath)) {
        $publicFiles = array_diff(scandir($publicPath), ['.', '..']);
    }

    // Check symlink
    $symlinkTarget = null;
    $symlinkExists = is_link(public_path('storage'));
    if ($symlinkExists) {
        $symlinkTarget = readlink(public_path('storage'));
    }

    return response()->json([
        'storage_path' => $storagePath,
        'storage_exists' => is_dir($storagePath),
        'storage_files' => array_values($storageFiles),
        'public_path' => $publicPath,
        'public_exists' => is_dir($publicPath),
        'public_files' => array_values($publicFiles),
        'symlink_exists' => $symlinkExists,
        'symlink_target' => $symlinkTarget,
        'user_profile_photo' => auth()->user()?->profile_photo,
    ]);
});

// TEMPORARY: Seed zonas_horarias table - REMOVE AFTER USE
Route::get('/seed-zonas-horarias', function () {
    try {
        // Create table if not exists
        if (!\Schema::hasTable('zonas_horarias')) {
            \Schema::create('zonas_horarias', function ($table) {
                $table->id();
                $table->string('nombre', 100);
                $table->string('codigo', 50);
                $table->string('offset', 20);
                $table->integer('offset_minutos')->default(0);
                $table->boolean('activo')->default(true);
                $table->integer('orden')->default(0);
                $table->timestamps();
            });
        }

        // Seed data
        $zonas = [
            [
                'nombre' => 'Zona Central / Ciudad de Mexico',
                'codigo' => 'America/Mexico_City',
                'offset' => 'GMT-6',
                'offset_minutos' => -360,
                'activo' => true,
                'orden' => 1,
            ],
            [
                'nombre' => 'Zona Pacifico / Tijuana',
                'codigo' => 'America/Tijuana',
                'offset' => 'GMT-8',
                'offset_minutos' => -480,
                'activo' => true,
                'orden' => 2,
            ],
            [
                'nombre' => 'Zona Noroeste / Hermosillo',
                'codigo' => 'America/Hermosillo',
                'offset' => 'GMT-7',
                'offset_minutos' => -420,
                'activo' => true,
                'orden' => 3,
            ],
            [
                'nombre' => 'Tiempo Universal Coordinado',
                'codigo' => 'UTC',
                'offset' => 'UTC',
                'offset_minutos' => 0,
                'activo' => true,
                'orden' => 10,
            ],
        ];

        foreach ($zonas as $zona) {
            \App\Models\ZonaHoraria::updateOrCreate(
                ['codigo' => $zona['codigo']],
                $zona
            );
        }

        $count = \App\Models\ZonaHoraria::count();

        return response()->json([
            'success' => true,
            'message' => "Zonas horarias seeded successfully. Total: {$count}",
            'zonas' => \App\Models\ZonaHoraria::orderBy('orden')->get()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
});

// Debug route - REMOVE IN PRODUCTION
Route::get('/debug-auth', function () {
    $user = auth()->user();
    if (!$user) {
        return response()->json([
            'logged_in' => false,
            'message' => 'No user logged in',
            'session_id' => session()->getId()
        ]);
    }
    return response()->json([
        'logged_in' => true,
        'user_id' => $user->id,
        'email' => $user->email,
        'email_verified' => $user->hasVerifiedEmail(),
        'profile_completed' => $user->profile_completed,
        'session_id' => session()->getId()
    ]);
});

