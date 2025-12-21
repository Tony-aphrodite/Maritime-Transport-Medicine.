<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    /**
     * Show the email verification notice
     */
    public function notice(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->route('profile.complete')
            : view('auth.verify-email');
    }

    /**
     * Handle the email verification
     */
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        // Log the verification
        try {
            AuditLog::logEvent(
                'email_verified',
                'success',
                [
                    'email' => $request->user()->email,
                ],
                $request->user()->id
            );
        } catch (\Exception $e) {
            Log::warning('Failed to log email verification: ' . $e->getMessage());
        }

        return redirect()->route('profile.complete')
            ->with('verified', true)
            ->with('success', '¡Su correo electrónico ha sido verificado! Ahora complete su perfil.');
    }

    /**
     * Resend the verification email
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('profile.complete');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Se ha enviado un nuevo enlace de verificación a su correo electrónico.');
    }
}
