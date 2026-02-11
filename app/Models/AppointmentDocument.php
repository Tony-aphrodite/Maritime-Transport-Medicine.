<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AppointmentDocument extends Model
{
    use HasFactory;

    /**
     * Storage disk to use (can be configured via environment)
     */
    protected static function getStorageDisk(): string
    {
        return env('DOCUMENT_STORAGE_DISK', 'public');
    }

    protected $fillable = [
        'user_id',
        'appointment_id',
        'document_type',
        'original_name',
        'file_path',
        'file_size',
        'mime_type',
        'status',
        'notes',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
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
     * Get the doctor/admin who reviewed this document.
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
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
     * Get status label in Spanish.
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'uploaded' => 'Subido',
            'pending_review' => 'Pendiente de Revision',
            'reviewed' => 'Revisado',
            'approved' => 'Aprobado',
            'rejected' => 'Rechazado',
            default => ucfirst($this->status),
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
     * Works with both local and S3 storage.
     */
    public function getUrlAttribute()
    {
        $disk = self::getStorageDisk();

        if ($disk === 's3') {
            return Storage::disk('s3')->url($this->file_path);
        }

        // For local/public disk, return asset URL
        return asset('storage/' . $this->file_path);
    }

    /**
     * Get temporary URL for secure access.
     * Only works with S3, returns regular URL for local storage.
     */
    public function getTemporaryUrl($minutes = 30)
    {
        $disk = self::getStorageDisk();

        if ($disk === 's3') {
            return Storage::disk('s3')->temporaryUrl($this->file_path, now()->addMinutes($minutes));
        }

        // For local storage, return regular URL
        return $this->url;
    }

    /**
     * Check if the file exists in storage.
     */
    public function fileExists(): bool
    {
        return Storage::disk(self::getStorageDisk())->exists($this->file_path);
    }

    /**
     * Get file contents.
     */
    public function getFileContents(): ?string
    {
        if (!$this->fileExists()) {
            return null;
        }
        return Storage::disk(self::getStorageDisk())->get($this->file_path);
    }

    /**
     * Delete the file from storage.
     */
    public function deleteFile(): bool
    {
        if ($this->fileExists()) {
            return Storage::disk(self::getStorageDisk())->delete($this->file_path);
        }
        return true;
    }

    /**
     * Mark document as reviewed.
     */
    public function markAsReviewed(int $reviewerId, string $status = 'reviewed', ?string $notes = null): bool
    {
        return $this->update([
            'status' => $status,
            'reviewed_at' => now(),
            'reviewed_by' => $reviewerId,
            'notes' => $notes,
        ]);
    }

    /**
     * Scope for documents pending review.
     */
    public function scopePendingReview($query)
    {
        return $query->whereIn('status', ['uploaded', 'pending_review']);
    }

    /**
     * Scope for reviewed documents.
     */
    public function scopeReviewed($query)
    {
        return $query->whereNotNull('reviewed_at');
    }

    /**
     * Scope for a specific document type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('document_type', $type);
    }
}
