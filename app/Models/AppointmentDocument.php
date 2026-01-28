<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AppointmentDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'appointment_id',
        'document_type',
        'original_name',
        'file_path',
        'file_size',
        'mime_type',
        'status',
    ];

    /**
     * Get the user that uploaded the document.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the appointment this document belongs to.
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get the document type label in Spanish.
     */
    public function getDocumentTypeLabelAttribute()
    {
        return match($this->document_type) {
            // Medical studies (new)
            'blood_test' => 'Biometria Hematica',
            'chemistry' => 'Quimica Sanguinea',
            'urine_test' => 'Examen General de Orina',
            'chest_xray' => 'Radiografia de Torax',
            'ecg' => 'Electrocardiograma',
            'vision_test' => 'Examen de Vista',
            'audiometry' => 'Audiometria',
            'other_medical' => 'Otros Estudios',
            // Legacy types (for existing data)
            'identification' => 'Identificacion Oficial',
            'medical_history' => 'Historial Medico',
            'sea_book' => 'Libreta de Mar',
            'photo' => 'Fotografia',
            'other' => 'Otro Documento',
            default => $this->document_type,
        };
    }

    /**
     * Get formatted file size.
     */
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' bytes';
    }

    /**
     * Get the full URL to the document.
     */
    public function getUrlAttribute()
    {
        return Storage::disk('s3')->url($this->file_path);
    }

    /**
     * Get temporary URL for secure access.
     */
    public function getTemporaryUrl($minutes = 30)
    {
        return Storage::disk('s3')->temporaryUrl($this->file_path, now()->addMinutes($minutes));
    }
}
