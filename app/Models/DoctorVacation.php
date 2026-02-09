<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorVacation extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'start_date',
        'end_date',
        'reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the doctor.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Check if vacation covers a specific date.
     */
    public function coversDate(string $date): bool
    {
        $checkDate = \Carbon\Carbon::parse($date)->startOfDay();
        return $checkDate >= $this->start_date->startOfDay()
            && $checkDate <= $this->end_date->startOfDay();
    }

    /**
     * Get duration in days.
     */
    public function getDurationDaysAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }
}
