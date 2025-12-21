<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Admin credentials (in production, these should be stored in database)
     */
    private $adminCredentials = [
        'email' => 'AdminJuan@gmail.com',
        'password' => 'johnson@suceess!'
    ];

    /**
     * Show main login form
     */
    public function showLogin()
    {
        // If user is already logged in, redirect appropriately
        if (Auth::check()) {
            $user = Auth::user();
            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }
            if (!$user->hasCompletedProfile()) {
                return redirect()->route('profile.complete');
            }
            return redirect()->route('dashboard');
        }

        // If admin is logged in
        if (Session::get('admin_logged_in', false)) {
            return redirect('/admin/dashboard');
        }

        return view('login');
    }

    /**
     * Process login attempt
     */
    public function processLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:3'
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        // Check if credentials match admin first
        if ($email === $this->adminCredentials['email'] && $password === $this->adminCredentials['password']) {
            // Admin login successful
            Session::put('admin_logged_in', true);
            Session::put('admin_email', $email);

            // Log successful admin login
            try {
                AuditLog::logEvent(
                    AuditLog::EVENT_LOGIN_SUCCESS,
                    AuditLog::STATUS_SUCCESS,
                    [
                        'authentication_method' => 'admin_credentials',
                        'email' => $email,
                        'login_type' => 'admin'
                    ],
                    $email
                );
            } catch (\Exception $e) {
                Log::warning('Failed to log admin login success: ' . $e->getMessage());
            }

            return redirect('/admin/dashboard')->with('success', 'Acceso administrativo exitoso');
        }

        // Try to authenticate as a regular user from database
        if (Auth::attempt(['email' => $email, 'password' => $password], $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Log successful user login
            try {
                AuditLog::logEvent(
                    AuditLog::EVENT_LOGIN_SUCCESS,
                    AuditLog::STATUS_SUCCESS,
                    [
                        'authentication_method' => 'database_credentials',
                        'email' => $email,
                        'login_type' => 'user',
                        'user_id' => $user->id,
                        'user_name' => $user->full_name
                    ],
                    $email
                );
            } catch (\Exception $e) {
                Log::warning('Failed to log user login success: ' . $e->getMessage());
            }

            // Check if email is verified
            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            // Check if profile is completed
            if (!$user->hasCompletedProfile()) {
                return redirect()->route('profile.complete');
            }

            return redirect()->intended('/dashboard')->with('success', 'Inicio de sesión exitoso');
        }

        // Invalid credentials
        try {
            AuditLog::logEvent(
                AuditLog::EVENT_LOGIN_FAILURE,
                AuditLog::STATUS_FAILURE,
                [
                    'authentication_method' => 'standard_credentials',
                    'email' => $email,
                    'reason' => 'invalid_credentials',
                    'login_type' => 'user'
                ],
                $email
            );
        } catch (\Exception $e) {
            Log::warning('Failed to log login failure: ' . $e->getMessage());
        }

        return back()->withInput()->withErrors([
            'email' => 'Las credenciales proporcionadas no son válidas.'
        ]);
    }

    /**
     * Logout (admin or regular user)
     */
    public function logout(Request $request)
    {
        $adminEmail = Session::get('admin_email');
        $isAdmin = Session::get('admin_logged_in', false);
        $userEmail = Auth::check() ? Auth::user()->email : null;

        // Log logout
        try {
            AuditLog::logEvent(
                $isAdmin ? AuditLog::EVENT_ADMIN_LOGOUT : 'user_logout',
                AuditLog::STATUS_SUCCESS,
                [
                    'logout_method' => 'manual',
                    'login_type' => $isAdmin ? 'admin' : 'user'
                ],
                $isAdmin ? $adminEmail : $userEmail
            );
        } catch (\Exception $e) {
            Log::warning('Failed to log logout: ' . $e->getMessage());
        }

        // Logout from Laravel Auth
        Auth::logout();

        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Sesión cerrada correctamente');
    }
}
