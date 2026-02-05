<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'specialty',
        'license_number',
        'timezone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the doctor's schedules.
     */
    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    /**
     * Get the doctor's vacations.
     */
    public function vacations()
    {
        return $this->hasMany(DoctorVacation::class);
    }

    /**
     * Get the doctor's blockages.
     */
    public function blockages()
    {
        return $this->hasMany(DoctorBlockage::class);
    }

    /**
     * Get the doctor's appointments.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the doctor's appointment holds.
     */
    public function holds()
    {
        return $this->hasMany(AppointmentHold::class);
    }

    /**
     * Check if doctor is on vacation on a specific date.
     */
    public function isOnVacation(string $date): bool
    {
        return $this->vacations()
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->exists();
    }

    /**
     * Check if a specific time slot is blocked.
     */
    public function isBlocked(string $date, string $time): bool
    {
        return $this->blockages()
            ->where('blocked_date', $date)
            ->where(function ($query) use ($time) {
                // Entire day blocked (no time specified)
                $query->whereNull('start_time')
                    ->orWhere(function ($q) use ($time) {
                        // Specific time range blocked
                        $q->where('start_time', '<=', $time)
                          ->where('end_time', '>', $time);
                    });
            })
            ->exists();
    }

    /**
     * Get schedule for a specific day of week.
     */
    public function getScheduleForDay(int $dayOfWeek): ?DoctorSchedule
    {
        return $this->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get available time slots for a specific date.
     */
    public function getAvailableSlotsForDate(string $date, string $userTimezone = 'America/Mexico_City'): array
    {
        $dateObj = Carbon::parse($date);
        $dayOfWeek = $dateObj->dayOfWeek;

        // Check if on vacation
        if ($this->isOnVacation($date)) {
            return [];
        }

        // Get schedule for this day
        $schedule = $this->getScheduleForDay($dayOfWeek);
        if (!$schedule) {
            return [];
        }

        // Generate time slots based on schedule
        $slots = [];
        $startTime = Carbon::parse($date . ' ' . $schedule->start_time, 'UTC');
        $endTime = Carbon::parse($date . ' ' . $schedule->end_time, 'UTC');
        $slotDuration = $schedule->slot_duration;
        $maxPerSlot = $schedule->max_appointments_per_slot;

        while ($startTime < $endTime) {
            $timeStr = $startTime->format('H:i');

            // Check if blocked
            if (!$this->isBlocked($date, $timeStr)) {
                // Count existing appointments for this slot
                $bookedCount = $this->appointments()
                    ->where('appointment_date', $date)
                    ->where('appointment_time', $timeStr)
                    ->whereIn('status', ['pending_payment', 'confirmed'])
                    ->count();

                // Check for active holds
                $holdCount = $this->holds()
                    ->where('appointment_date', $date)
                    ->where('appointment_time', $timeStr)
                    ->where('expires_at', '>', now())
                    ->count();

                $totalOccupied = $bookedCount + $holdCount;
                $available = $totalOccupied < $maxPerSlot;

                // Convert to user timezone for display
                $userTime = $startTime->copy()->setTimezone($userTimezone);

                $slots[] = [
                    'time_utc' => $timeStr,
                    'time_display' => $userTime->format('H:i'),
                    'available' => $available,
                    'booked' => $bookedCount,
                    'held' => $holdCount,
                    'remaining' => max(0, $maxPerSlot - $totalOccupied),
                ];
            }

            $startTime->addMinutes($slotDuration);
        }

        return $slots;
    }

    /**
     * Scope for active doctors.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
