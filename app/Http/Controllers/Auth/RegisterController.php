<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Por favor ingrese un correo electrónico válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        try {
            // Create the user
            $user = User::create([
                'email' => $validated['email'],
                'password' => $validated['password'],
                'account_status' => 'pending_verification',
                'profile_completed' => false,
                'registration_ip' => $request->ip(),
                'registration_user_agent' => $request->userAgent(),
            ]);

            // Fire registered event (sends verification email)
            event(new Registered($user));

            // Log the registration
            try {
                AuditLog::logEvent(
                    'account_registered',
                    'success',
                    [
                        'email' => $validated['email'],
                        'registration_method' => 'email',
                    ],
                    $user->id
                );
            } catch (\Exception $e) {
                Log::warning('Failed to log registration: ' . $e->getMessage());
            }

            // Log in the user
            Auth::login($user);

            // Redirect to verification notice page
            return redirect()->route('verification.notice')
                ->with('success', 'Registro exitoso. Por favor verifique su correo electrónico.');

        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Error al registrar. Por favor intente nuevamente.']);
        }
    }
}
