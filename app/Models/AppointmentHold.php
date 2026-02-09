<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AppointmentHold extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'doctor_id',
        'appointment_date',
        'appointment_time',
        'expires_at',
        'session_id',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'expires_at' => 'datetime',
    ];

    /**
     * Hold duration in minutes.
     */
    const HOLD_DURATION_MINUTES = 15;

    /**
     * Get the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the doctor.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Check if hold is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at < now();
    }

    /**
     * Check if hold is still active.
     */
    public function isActive(): bool
    {
        return !$this->isExpired();
    }

    /**
     * Get remaining time in seconds.
     */
    public function getRemainingSecondsAttribute(): int
    {
        if ($this->isExpired()) {
            return 0;
        }
        return now()->diffInSeconds($this->expires_at, false);
    }

    /**
     * Get remaining time formatted (MM:SS).
     */
    public function getRemainingTimeAttribute(): string
    {
        $seconds = $this->remaining_seconds;
        if ($seconds <= 0) {
            return '00:00';
        }
        $minutes = floor($seconds / 60);
        $secs = $seconds % 60;
        return sprintf('%02d:%02d', $minutes, $secs);
    }

    /**
     * Create a new hold for a slot.
     */
    public static function createHold(int $userId, int $doctorId, string $date, string $time, ?string $sessionId = null): self
    {
        // Remove any existing hold for this user
        self::where('user_id', $userId)->delete();

        return self::create([
            'user_id' => $userId,
            'doctor_id' => $doctorId,
            'appointment_date' => $date,
            'appointment_time' => $time,
            'expires_at' => now()->addMinutes(self::HOLD_DURATION_MINUTES),
            'session_id' => $sessionId,
        ]);
    }

    /**
     * Release hold for a user.
     */
    public static function releaseHold(int $userId): bool
    {
        return self::where('user_id', $userId)->delete() > 0;
    }

    /**
     * Clean up expired holds.
     */
    public static function cleanupExpired(): int
    {
        return self::where('expires_at', '<', now())->delete();
    }

    /**
     * Scope for active (non-expired) holds.
     */
    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }

    /**
     * Scope for expired holds.
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * Scope for a specific slot.
     */
    public function scopeForSlot($query, int $doctorId, string $date, string $time)
    {
        return $query->where('doctor_id', $doctorId)
            ->where('appointment_date', $date)
            ->where('appointment_time', $time);
    }
}
