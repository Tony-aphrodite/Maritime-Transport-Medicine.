<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\AuditLog;

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

        // Check if credentials match admin
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

        } else {
            // Check for registered users in session (in production, check database)
            $registeredUser = Session::get('registered_user');
            if ($registeredUser && 
                $registeredUser['email'] === $email && 
                Hash::check($password, $registeredUser['password'])) {
                
                // Log successful user login
                try {
                    AuditLog::logEvent(
                        AuditLog::EVENT_LOGIN_SUCCESS,
                        AuditLog::STATUS_SUCCESS,
                        [
                            'authentication_method' => 'registered_user_credentials',
                            'email' => $email,
                            'login_type' => 'user',
                            'user_name' => $registeredUser['nombres'] . ' ' . $registeredUser['apellido_paterno']
                        ],
                        $email
                    );
                } catch (\Exception $e) {
                    Log::warning('Failed to log user login success: ' . $e->getMessage());
                }

                // Set user session
                Session::put('user_logged_in', true);
                Session::put('user_email', $email);
                Session::put('user_data', $registeredUser);

                return redirect('/dashboard')->with('success', 'Inicio de sesión exitoso');
                
            } else {
                // Invalid credentials for both admin and registered users
            
            // Log failed login attempt
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
        }
    }

    /**
     * Show main login form
     */
    public function showLogin()
    {
        return view('login');
    }

    /**
     * Logout (admin or regular user)
     */
    public function logout()
    {
        $adminEmail = Session::get('admin_email');
        $isAdmin = Session::get('admin_logged_in', false);

        // Log logout
        try {
            AuditLog::logEvent(
                $isAdmin ? AuditLog::EVENT_ADMIN_LOGOUT : AuditLog::EVENT_LOGIN_FAILURE,
                AuditLog::STATUS_SUCCESS,
                [
                    'logout_method' => 'manual',
                    'login_type' => $isAdmin ? 'admin' : 'user'
                ],
                $adminEmail
            );
        } catch (\Exception $e) {
            Log::warning('Failed to log logout: ' . $e->getMessage());
        }

        Session::flush();

        return redirect('/login')->with('success', 'Sesión cerrada correctamente');
    }
}