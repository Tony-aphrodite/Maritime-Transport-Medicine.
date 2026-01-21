<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
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

        // Mark profile as completed for new users
        $user = $request->user();
        if (!$user->profile_completed) {
            $user->update(['profile_completed' => true]);
        }

        // Logout user so they can login fresh from landing page
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to landing page with verified parameter
        return redirect('/?verified=true');
    }

    /**
     * Handle email verification without requiring auth (for email link clicks)
     */
    public function verifyWithoutAuth(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        // Check if hash matches
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect('/?error=invalid_link');
        }

        // Check if already verified
        if ($user->hasVerifiedEmail()) {
            return redirect('/?verified=true');
        }

        // Mark email as verified
        $user->markEmailAsVerified();

        // Log the verification
        try {
            AuditLog::logEvent(
                'email_verified',
                'success',
                ['email' => $user->email],
                $user->id
            );
        } catch (\Exception $e) {
            Log::warning('Failed to log email verification: ' . $e->getMessage());
        }

        // Mark profile as completed
        if (!$user->profile_completed) {
            $user->update(['profile_completed' => true]);
        }

        // Redirect to landing page with verified parameter
        return redirect('/?verified=true');
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
