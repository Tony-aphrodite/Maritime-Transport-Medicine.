<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ParentalConsent extends Model
{
    use HasFactory;

    protected $fillable = [
        'minor_email',
        'minor_full_name',
        'minor_birth_date',
        'parent_full_name',
        'parent_email',
        'parent_phone',
        'relationship',
        'consent_token',
        'status',
        'consent_requested_at',
        'consent_given_at',
        'expires_at',
        'consent_data',
        'ip_address',
        'user_agent',
        'digital_signature',
        'terms_accepted'
    ];

    protected $casts = [
        'minor_birth_date' => 'date',
        'consent_requested_at' => 'datetime',
        'consent_given_at' => 'datetime',
        'expires_at' => 'datetime',
        'consent_data' => 'json',
    ];

    /**
     * Status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_DENIED = 'denied';
    const STATUS_EXPIRED = 'expired';

    /**
     * Generate a unique consent token
     */
    public static function generateConsentToken(): string
    {
        return Str::random(64);
    }

    /**
     * Create a new parental consent request
     */
    public static function createConsentRequest(array $data): self
    {
        return self::create([
            'minor_email' => $data['minor_email'],
            'minor_full_name' => $data['minor_full_name'],
            'minor_birth_date' => $data['minor_birth_date'],
            'parent_full_name' => $data['parent_full_name'],
            'parent_email' => $data['parent_email'],
            'parent_phone' => $data['parent_phone'] ?? null,
            'relationship' => $data['relationship'] ?? 'parent',
            'consent_token' => self::generateConsentToken(),
            'status' => self::STATUS_PENDING,
            'consent_requested_at' => now(),
            'expires_at' => now()->addDays(7), // 7 days to respond
            'consent_data' => $data['additional_data'] ?? null,
        ]);
    }

    /**
     * Approve the consent request
     */
    public function approve(array $approvalData = []): bool
    {
        return $this->update([
            'status' => self::STATUS_APPROVED,
            'consent_given_at' => now(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'digital_signature' => $approvalData['digital_signature'] ?? null,
            'terms_accepted' => $approvalData['terms_accepted'] ?? null,
        ]);
    }

    /**
     * Deny the consent request
     */
    public function deny(): bool
    {
        return $this->update([
            'status' => self::STATUS_DENIED,
            'consent_given_at' => now(),
        ]);
    }

    /**
     * Check if consent request has expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Mark as expired
     */
    public function markAsExpired(): bool
    {
        return $this->update(['status' => self::STATUS_EXPIRED]);
    }

    /**
     * Check if consent is approved and valid
     */
    public function isValid(): bool
    {
        return $this->status === self::STATUS_APPROVED && !$this->isExpired();
    }

    /**
     * Get consent by token
     */
    public static function findByToken(string $token): ?self
    {
        return self::where('consent_token', $token)->first();
    }

    /**
     * Check if minor already has pending consent request
     */
    public static function hasPendingConsent(string $minorEmail): bool
    {
        return self::where('minor_email', $minorEmail)
            ->where('status', self::STATUS_PENDING)
            ->where('expires_at', '>', now())
            ->exists();
    }

    /**
     * Get valid consent for minor
     */
    public static function getValidConsentForMinor(string $minorEmail): ?self
    {
        return self::where('minor_email', $minorEmail)
            ->where('status', self::STATUS_APPROVED)
            ->where('expires_at', '>', now())
            ->first();
    }

    /**
     * Calculate minor's age based on birth date
     */
    public function getMinorAgeAttribute(): int
    {
        return Carbon::parse($this->minor_birth_date)->age;
    }

    /**
     * Check if this is for a minor (under 18)
     */
    public function isForMinor(): bool
    {
        return $this->minor_age < 18;
    }

    /**
     * Relationship with the user (minor)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'minor_email', 'email');
    }

    /**
     * Log consent activity to audit system
     */
    public function logConsentActivity(string $action, array $data = []): void
    {
        AuditLog::logEvent(
            "parental_consent_{$action}",
            $this->status === self::STATUS_APPROVED ? AuditLog::STATUS_SUCCESS : AuditLog::STATUS_PENDING,
            array_merge([
                'consent_token' => $this->consent_token,
                'minor_email' => $this->minor_email,
                'parent_email' => $this->parent_email,
                'action' => $action
            ], $data),
            $this->minor_email,
            $this->consent_token
        );
    }
}
