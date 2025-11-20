<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParentalConsent;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ParentalConsentController extends Controller
{
    /**
     * Show the parental consent form
     */
    public function showConsentForm($token)
    {
        $consent = ParentalConsent::findByToken($token);
        
        if (!$consent) {
            return view('parental-consent.not-found');
        }
        
        if ($consent->isExpired()) {
            $consent->markAsExpired();
            return view('parental-consent.expired', compact('consent'));
        }
        
        if ($consent->status !== ParentalConsent::STATUS_PENDING) {
            return view('parental-consent.already-processed', compact('consent'));
        }
        
        // Log consent form view
        AuditLog::logEvent(
            'parental_consent_form_viewed',
            AuditLog::STATUS_IN_PROGRESS,
            [
                'consent_id' => $consent->id,
                'minor_email' => $consent->minor_email,
                'parent_email' => $consent->parent_email
            ]
        );
        
        return view('parental-consent.form', compact('consent'));
    }
    
    /**
     * Process the parental consent decision
     */
    public function processConsent(Request $request, $token)
    {
        $consent = ParentalConsent::findByToken($token);
        
        if (!$consent || $consent->status !== ParentalConsent::STATUS_PENDING || $consent->isExpired()) {
            return redirect()->route('parental.consent.form', $token)
                           ->with('error', 'Enlace de consentimiento no válido o expirado.');
        }
        
        $request->validate([
            'decision' => 'required|in:approve,deny',
            'digital_signature' => 'required|string|min:3',
            'terms_accepted' => 'required|accepted'
        ]);
        
        $decision = $request->input('decision');
        
        if ($decision === 'approve') {
            $consent->approve([
                'digital_signature' => $request->input('digital_signature'),
                'terms_accepted' => 'yes'
            ]);
            
            // Log successful approval
            AuditLog::logEvent(
                'parental_consent_approved',
                AuditLog::STATUS_SUCCESS,
                [
                    'consent_id' => $consent->id,
                    'minor_email' => $consent->minor_email,
                    'parent_email' => $consent->parent_email,
                    'digital_signature_provided' => true
                ]
            );
            
            // TODO: Send notification email to minor
            // TODO: Enable the minor's account for completion
            
            return view('parental-consent.success', compact('consent'));
            
        } else {
            $consent->deny();
            
            // Log denial
            AuditLog::logEvent(
                'parental_consent_denied',
                AuditLog::STATUS_FAILURE,
                [
                    'consent_id' => $consent->id,
                    'minor_email' => $consent->minor_email,
                    'parent_email' => $consent->parent_email
                ]
            );
            
            // TODO: Send notification email to minor
            
            return view('parental-consent.denied', compact('consent'));
        }
    }
    
    /**
     * Check the status of a parental consent request
     */
    public function checkStatus($token)
    {
        $consent = ParentalConsent::findByToken($token);
        
        if (!$consent) {
            return response()->json(['error' => 'Consent not found'], 404);
        }
        
        return response()->json([
            'status' => $consent->status,
            'minor_name' => $consent->minor_full_name,
            'parent_name' => $consent->parent_full_name,
            'expires_at' => $consent->expires_at->toISOString(),
            'is_expired' => $consent->isExpired(),
            'is_valid' => $consent->isValid()
        ]);
    }
    
    /**
     * Request parental consent (called from registration)
     */
    public static function requestParentalConsent($minorData, $parentData)
    {
        try {
            // Check if there's already a pending consent request
            if (ParentalConsent::hasPendingConsent($minorData['email'])) {
                return [
                    'success' => false,
                    'message' => 'Ya existe una solicitud de consentimiento pendiente para este menor.'
                ];
            }
            
            // Create the consent request
            $consent = ParentalConsent::createConsentRequest([
                'minor_email' => $minorData['email'],
                'minor_full_name' => $minorData['full_name'],
                'minor_birth_date' => $minorData['birth_date'],
                'parent_full_name' => $parentData['full_name'],
                'parent_email' => $parentData['email'],
                'parent_phone' => $parentData['phone'],
                'relationship' => $parentData['relationship']
            ]);
            
            // Send email to parent (TODO: implement email template)
            // Mail::to($consent->parent_email)->send(new ParentalConsentRequest($consent));
            
            // Log the consent request
            AuditLog::logEvent(
                'parental_consent_requested',
                AuditLog::STATUS_PENDING,
                [
                    'consent_id' => $consent->id,
                    'minor_email' => $consent->minor_email,
                    'parent_email' => $consent->parent_email,
                    'expires_at' => $consent->expires_at
                ]
            );
            
            return [
                'success' => true,
                'consent_id' => $consent->id,
                'message' => 'Solicitud de consentimiento parental enviada exitosamente.'
            ];
            
        } catch (\Exception $e) {
            Log::error('Failed to create parental consent request: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Error al enviar la solicitud de consentimiento parental.'
            ];
        }
    }
}
