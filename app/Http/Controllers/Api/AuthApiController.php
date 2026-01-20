<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class AuthApiController extends Controller
{
    /**
     * Handle login request via AJAX
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            AuditLog::logEvent(
                AuditLog::EVENT_LOGIN_FAILURE,
                $request->email,
                AuditLog::STATUS_FAILURE,
                ['reason' => 'User not found'],
                $request
            );

            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            AuditLog::logEvent(
                AuditLog::EVENT_LOGIN_FAILURE,
                $request->email,
                AuditLog::STATUS_FAILURE,
                ['reason' => 'Invalid password'],
                $request
            );

            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        // Check if email is verified
        if (!$user->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'Por favor verifica tu correo electrónico',
                'requires_verification' => true
            ], 403);
        }

        // Log the user in
        Auth::login($user);

        AuditLog::logEvent(
            AuditLog::EVENT_LOGIN_SUCCESS,
            $user->email,
            AuditLog::STATUS_SUCCESS,
            ['user_id' => $user->id],
            $request
        );

        // Determine redirect based on profile completion
        $redirect = $user->hasCompletedProfile() ? '/dashboard' : '/complete-profile';

        return response()->json([
            'success' => true,
            'message' => '¡Inicio de sesión exitoso!',
            'redirect' => $redirect,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'profile_completed' => $user->hasCompletedProfile()
            ]
        ]);
    }

    /**
     * Handle registration request via AJAX
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.required' => 'El correo electrónico es requerido',
            'email.email' => 'Ingresa un correo electrónico válido',
            'email.unique' => 'Este correo electrónico ya está registrado',
            'password.required' => 'La contraseña es requerida',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create the user
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'account_status' => User::ACCOUNT_STATUS_PENDING_VERIFICATION,
                'registration_ip' => $request->ip(),
                'registration_user_agent' => $request->userAgent(),
            ]);

            // Fire the Registered event (sends verification email)
            event(new Registered($user));

            AuditLog::logEvent(
                'registration_completed',
                $user->email,
                AuditLog::STATUS_SUCCESS,
                ['user_id' => $user->id],
                $request
            );

            return response()->json([
                'success' => true,
                'message' => '¡Registro exitoso! Por favor verifica tu correo electrónico.',
                'email' => $user->email
            ]);

        } catch (\Exception $e) {
            AuditLog::logEvent(
                'registration_failed',
                $request->email,
                AuditLog::STATUS_FAILURE,
                ['error' => $e->getMessage()],
                $request
            );

            return response()->json([
                'success' => false,
                'message' => 'Error al registrar. Por favor intenta de nuevo.'
            ], 500);
        }
    }

    /**
     * Resend verification email
     */
    public function resendVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Correo electrónico inválido'
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró una cuenta con este correo'
            ], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'Este correo ya ha sido verificado'
            ], 400);
        }

        // Resend verification email
        $user->sendEmailVerificationNotification();

        AuditLog::logEvent(
            'verification_email_resent',
            $user->email,
            AuditLog::STATUS_SUCCESS,
            ['user_id' => $user->id],
            $request
        );

        return response()->json([
            'success' => true,
            'message' => '¡Correo de verificación reenviado!'
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            AuditLog::logEvent(
                'logout',
                $user->email,
                AuditLog::STATUS_SUCCESS,
                ['user_id' => $user->id],
                $request
            );
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada exitosamente'
        ]);
    }
}
