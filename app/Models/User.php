<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'curp',
        'phone_number',
        'birth_date',
        'gender',
        'curp_verification_status',
        'face_verification_status',
        'document_verification_status',
        'account_status',
        'maritime_license_number',
        'vessel_name',
        'company_name',
        'user_type',
        'verification_metadata',
        'registration_ip',
        'registration_user_agent',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birth_date' => 'date',
        'curp_verified_at' => 'datetime',
        'face_verified_at' => 'datetime',
        'documents_verified_at' => 'datetime',
        'last_verification_attempt' => 'datetime',
        'verification_metadata' => 'json',
    ];

    /**
     * Status constants for better type safety
     */
    const VERIFICATION_STATUS_PENDING = 'pending';
    const VERIFICATION_STATUS_VERIFIED = 'verified';
    const VERIFICATION_STATUS_FAILED = 'failed';
    const VERIFICATION_STATUS_NOT_REQUIRED = 'not_required';
    
    const ACCOUNT_STATUS_ACTIVE = 'active';
    const ACCOUNT_STATUS_PENDING_VERIFICATION = 'pending_verification';
    const ACCOUNT_STATUS_SUSPENDED = 'suspended';
    const ACCOUNT_STATUS_INACTIVE = 'inactive';
    
    const USER_TYPE_INDIVIDUAL = 'individual';
    const USER_TYPE_COMPANY = 'company';
    const USER_TYPE_MARITIME_PROFESSIONAL = 'maritime_professional';
    const USER_TYPE_MEDICAL_PROFESSIONAL = 'medical_professional';

    /**
     * Check if user has completed CURP verification
     */
    public function hasCurpVerified(): bool
    {
        return $this->curp_verification_status === self::VERIFICATION_STATUS_VERIFIED;
    }

    /**
     * Check if user has completed face verification
     */
    public function hasFaceVerified(): bool
    {
        return $this->face_verification_status === self::VERIFICATION_STATUS_VERIFIED;
    }

    /**
     * Check if user has completed document verification
     */
    public function hasDocumentsVerified(): bool
    {
        return $this->document_verification_status === self::VERIFICATION_STATUS_VERIFIED;
    }

    /**
     * Check if user has completed all verifications
     */
    public function isFullyVerified(): bool
    {
        return $this->hasCurpVerified() && 
               $this->hasFaceVerified() && 
               $this->hasDocumentsVerified();
    }

    /**
     * Check if user account is active
     */
    public function isActive(): bool
    {
        return $this->account_status === self::ACCOUNT_STATUS_ACTIVE;
    }

    /**
     * Mark CURP as verified
     */
    public function markCurpVerified(): bool
    {
        return $this->update([
            'curp_verification_status' => self::VERIFICATION_STATUS_VERIFIED,
            'curp_verified_at' => now()
        ]);
    }

    /**
     * Mark face verification as completed
     */
    public function markFaceVerified(): bool
    {
        return $this->update([
            'face_verification_status' => self::VERIFICATION_STATUS_VERIFIED,
            'face_verified_at' => now()
        ]);
    }

    /**
     * Mark documents as verified
     */
    public function markDocumentsVerified(): bool
    {
        return $this->update([
            'document_verification_status' => self::VERIFICATION_STATUS_VERIFIED,
            'documents_verified_at' => now()
        ]);
    }

    /**
     * Activate account if all verifications are complete
     */
    public function activateIfVerified(): bool
    {
        if ($this->isFullyVerified()) {
            return $this->update(['account_status' => self::ACCOUNT_STATUS_ACTIVE]);
        }
        return false;
    }

    /**
     * Get user's age based on birth date
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->birth_date) {
            return null;
        }
        return $this->birth_date->age;
    }

    /**
     * Check if user is a minor (under 18)
     */
    public function isMinor(): bool
    {
        return $this->age !== null && $this->age < 18;
    }

    /**
     * Relationship with audit logs
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'user_id', 'curp');
    }

    /**
     * Relationship with parental consent (if user is a minor)
     */
    public function parentalConsent()
    {
        return $this->hasOne(ParentalConsent::class, 'minor_email', 'email');
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('account_status', self::ACCOUNT_STATUS_ACTIVE);
    }

    /**
     * Scope for verified users
     */
    public function scopeVerified($query)
    {
        return $query->where('curp_verification_status', self::VERIFICATION_STATUS_VERIFIED)
                    ->where('face_verification_status', self::VERIFICATION_STATUS_VERIFIED)
                    ->where('document_verification_status', self::VERIFICATION_STATUS_VERIFIED);
    }

    /**
     * Scope for maritime professionals
     */
    public function scopeMaritimeProfessionals($query)
    {
        return $query->where('user_type', self::USER_TYPE_MARITIME_PROFESSIONAL);
    }
}
