<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmailNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'profile_completed',
        'curp',
        'rfc',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'telefono_movil',
        'nacionalidad',
        'sexo',
        'fecha_nacimiento',
        'pais_nacimiento',
        'estado_nacimiento',
        'estado',
        'municipio',
        'localidad',
        'codigo_postal',
        'calle',
        'numero_exterior',
        'numero_interior',
        'curp_verification_status',
        'face_verification_status',
        'account_status',
        'curp_verified_at',
        'face_verified_at',
        'face_verification_confidence',
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
        'profile_completed' => 'boolean',
        'fecha_nacimiento' => 'date',
        'curp_verified_at' => 'datetime',
        'face_verified_at' => 'datetime',
        'face_verification_confidence' => 'decimal:2',
        'verification_metadata' => 'json',
    ];

    /**
     * Status constants
     */
    const VERIFICATION_STATUS_PENDING = 'pending';
    const VERIFICATION_STATUS_VERIFIED = 'verified';
    const VERIFICATION_STATUS_FAILED = 'failed';

    const ACCOUNT_STATUS_ACTIVE = 'active';
    const ACCOUNT_STATUS_PENDING_VERIFICATION = 'pending_verification';
    const ACCOUNT_STATUS_SUSPENDED = 'suspended';
    const ACCOUNT_STATUS_INACTIVE = 'inactive';

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailNotification);
    }

    /**
     * Get user's full name
     */
    public function getFullNameAttribute(): string
    {
        if (!$this->nombres) {
            return $this->email;
        }
        $name = $this->nombres . ' ' . $this->apellido_paterno;
        if ($this->apellido_materno) {
            $name .= ' ' . $this->apellido_materno;
        }
        return $name;
    }

    /**
     * Check if user has completed their profile
     */
    public function hasCompletedProfile(): bool
    {
        return $this->profile_completed === true;
    }

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
     * Check if user has completed all verifications
     */
    public function isFullyVerified(): bool
    {
        return $this->hasCurpVerified() && $this->hasFaceVerified();
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
    public function markFaceVerified(float $confidence = null): bool
    {
        $data = [
            'face_verification_status' => self::VERIFICATION_STATUS_VERIFIED,
            'face_verified_at' => now()
        ];

        if ($confidence !== null) {
            $data['face_verification_confidence'] = $confidence;
        }

        return $this->update($data);
    }

    /**
     * Mark profile as completed and activate account
     */
    public function markProfileCompleted(): bool
    {
        return $this->update([
            'profile_completed' => true,
            'account_status' => self::ACCOUNT_STATUS_ACTIVE
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
        if (!$this->fecha_nacimiento) {
            return null;
        }
        return $this->fecha_nacimiento->age;
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
        return $this->hasMany(AuditLog::class, 'user_id', 'id');
    }

    /**
     * Relationship with parental consent (if user is a minor)
     */
    public function parentalConsent()
    {
        return $this->hasOne(ParentalConsent::class, 'minor_curp', 'curp');
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
                    ->where('face_verification_status', self::VERIFICATION_STATUS_VERIFIED);
    }

    /**
     * Get full address as a string
     */
    public function getFullAddressAttribute(): string
    {
        if (!$this->calle) {
            return '';
        }
        $address = $this->calle . ' ' . $this->numero_exterior;
        if ($this->numero_interior) {
            $address .= ' Int. ' . $this->numero_interior;
        }
        $address .= ', ' . $this->localidad . ', ' . $this->municipio . ', ' . $this->estado . ' C.P. ' . $this->codigo_postal;
        return $address;
    }
}
