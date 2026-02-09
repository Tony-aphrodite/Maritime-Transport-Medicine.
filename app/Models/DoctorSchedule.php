<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'day_of_week',
        'start_time',
        'end_time',
        'slot_duration',
        'max_appointments_per_slot',
        'is_active',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'slot_duration' => 'integer',
        'max_appointments_per_slot' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Day of week labels in Spanish.
     */
    const DAY_LABELS = [
        0 => 'Domingo',
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miercoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sabado',
    ];

    /**
     * Get the doctor.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get day name in Spanish.
     */
    public function getDayNameAttribute(): string
    {
        return self::DAY_LABELS[$this->day_of_week] ?? 'Desconocido';
    }

    /**
     * Get formatted time range.
     */
    public function getTimeRangeAttribute(): string
    {
        return substr($this->start_time, 0, 5) . ' - ' . substr($this->end_time, 0, 5);
    }
}
