<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorBlockage extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'blocked_date',
        'start_time',
        'end_time',
        'reason',
    ];

    protected $casts = [
        'blocked_date' => 'date',
    ];

    /**
     * Get the doctor.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Check if this blockage covers a specific time.
     */
    public function coversTime(string $time): bool
    {
        // If no time specified, entire day is blocked
        if (is_null($this->start_time) || is_null($this->end_time)) {
            return true;
        }

        return $time >= $this->start_time && $time < $this->end_time;
    }

    /**
     * Check if entire day is blocked.
     */
    public function isFullDayBlock(): bool
    {
        return is_null($this->start_time) || is_null($this->end_time);
    }

    /**
     * Get formatted time range.
     */
    public function getTimeRangeAttribute(): string
    {
        if ($this->isFullDayBlock()) {
            return 'Todo el dia';
        }
        return substr($this->start_time, 0, 5) . ' - ' . substr($this->end_time, 0, 5);
    }
}
