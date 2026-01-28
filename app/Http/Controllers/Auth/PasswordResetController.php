<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class PasswordResetController extends Controller
{
    /**
     * Show the password reset request form.
     */
    public function showRequestForm()
    {
        return view('password-reset');
    }

    /**
     * Send a password reset link to the given user.
     */
    public function sendResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ], [
            'email.required' => 'El correo electrónico es requerido.',
            'email.email' => 'Ingresa un correo electrónico válido.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if user exists
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // For security, we still show success message even if user doesn't exist
            // This prevents email enumeration attacks
            return back()->with('status', 'Si existe una cuenta con este correo electrónico, recibirás un enlace de recuperación.');
        }

        // Send the password reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Log the password reset request
        try {
            AuditLog::logEvent(
                'password_reset_requested',
                $status === Password::RESET_LINK_SENT ? AuditLog::STATUS_SUCCESS : AuditLog::STATUS_FAILURE,
                ['email' => $request->email],
                $request->email
            );
        } catch (\Exception $e) {
            // Silently ignore logging errors
        }

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Se ha enviado un enlace de recuperación a tu correo electrónico.');
        }

        return back()->withErrors(['email' => $this->getErrorMessage($status)]);
    }

    /**
     * Show the password reset form.
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.password-reset-form', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Reset the user's password.
     */
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'token.required' => 'El token de recuperación es inválido.',
            'email.required' => 'El correo electrónico es requerido.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'password.required' => 'La contraseña es requerida.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));

                // Log the successful password reset
                try {
                    AuditLog::logEvent(
                        'password_reset_completed',
                        AuditLog::STATUS_SUCCESS,
                        ['user_id' => $user->id],
                        $user->email
                    );
                } catch (\Exception $e) {
                    // Silently ignore logging errors
                }
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('home')->with('success', '¡Tu contraseña ha sido restablecida! Ya puedes iniciar sesión.');
        }

        return back()->withErrors(['email' => $this->getErrorMessage($status)]);
    }

    /**
     * Get the error message for the given status.
     */
    private function getErrorMessage($status)
    {
        $messages = [
            Password::RESET_LINK_SENT => 'Se ha enviado un enlace de recuperación.',
            Password::PASSWORD_RESET => 'Tu contraseña ha sido restablecida.',
            Password::INVALID_USER => 'No encontramos un usuario con ese correo electrónico.',
            Password::INVALID_TOKEN => 'El enlace de recuperación es inválido o ha expirado.',
            Password::RESET_THROTTLED => 'Por favor espera antes de intentar de nuevo.',
        ];

        return $messages[$status] ?? 'Ha ocurrido un error. Por favor intenta de nuevo.';
    }
}
