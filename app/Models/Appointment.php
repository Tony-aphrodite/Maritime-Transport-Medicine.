<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'appointment_date',
        'appointment_time',
        'timezone',
        'exam_type',
        'years_at_sea',
        'current_position',
        'vessel_type',
        'has_chronic_conditions',
        'chronic_conditions_detail',
        'takes_medications',
        'medications_detail',
        'has_allergies',
        'allergies_detail',
        'has_surgeries',
        'surgeries_detail',
        'workplace_risks',
        'additional_notes',
        'subtotal',
        'tax',
        'total',
        'status',
        'payment_status',
        'payment_date',
        'payment_reference',
        'payment_method',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'payment_date' => 'datetime',
        'has_chronic_conditions' => 'boolean',
        'takes_medications' => 'boolean',
        'has_allergies' => 'boolean',
        'has_surgeries' => 'boolean',
        'workplace_risks' => 'array',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Get the user that owns the appointment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the documents for the appointment.
     */
    public function documents()
    {
        return $this->hasMany(AppointmentDocument::class);
    }

    /**
     * Check if appointment is confirmed.
     */
    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if payment is complete.
     */
    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Get formatted appointment datetime.
     */
    public function getFormattedDateTimeAttribute()
    {
        return $this->appointment_date->format('d/m/Y') . ' a las ' . $this->appointment_time;
    }

    /**
     * Get status label in Spanish.
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending_payment' => 'Pendiente de Pago',
            'confirmed' => 'Confirmada',
            'completed' => 'Completada',
            'cancelled' => 'Cancelada',
            default => 'Desconocido',
        };
    }

    /**
     * Get exam type label in Spanish.
     */
    public function getExamTypeLabelAttribute()
    {
        return match($this->exam_type) {
            'new' => 'Examen Nuevo',
            'renewal' => 'Renovacion',
            default => $this->exam_type,
        };
    }
}
