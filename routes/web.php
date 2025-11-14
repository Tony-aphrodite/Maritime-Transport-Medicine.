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
