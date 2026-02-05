<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentDocument;
use App\Models\AppointmentHold;
use App\Models\AuditLog;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Show Step 1 - Date and Time Selection
     */
    public function step1()
    {
        $user = Auth::user();

        // Check if profile is completed before allowing appointment booking
        if (!$user->hasCompletedProfile()) {
            return redirect()->route('dashboard')
                ->with('error', 'Debe completar su perfil antes de agendar una cita.');
        }

        // Check if user already has an active appointment
        if ($user->hasActiveAppointment()) {
            $activeAppointment = $user->getActiveAppointment();
            return redirect()->route('dashboard')
                ->with('error', 'Ya tiene una cita activa programada para el ' . $activeAppointment->formatted_date_time . '. No puede agendar otra cita hasta que esta sea completada o cancelada.');
        }

        // Clean up expired holds
        AppointmentHold::cleanupExpired();

        // Get active doctor (for now, get first active doctor)
        $doctor = Doctor::active()->first();

        // Check if user has an existing hold
        $existingHold = AppointmentHold::where('user_id', $user->id)->active()->first();

        // Get user's preferred timezone from session or default
        $userTimezone = session('appointment.timezone', 'America/Mexico_City');

        // Get available time slots for the next 30 days
        $availableSlots = $this->getAvailableSlots($doctor, $userTimezone);

        return view('appointments.step1', compact('user', 'availableSlots', 'doctor', 'existingHold', 'userTimezone'));
    }

    /**
     * Process Step 1 - Save selected date and time
     */
    public function processStep1(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|string',
            'timezone' => 'nullable|string',
            'doctor_id' => 'nullable|exists:doctors,id',
        ]);

        $timezone = $validated['timezone'] ?? 'America/Mexico_City';
        $doctorId = $validated['doctor_id'] ?? Doctor::active()->first()?->id;

        if (!$doctorId) {
            return back()->with('error', 'No hay medicos disponibles en este momento.');
        }

        // Create or update hold for this slot
        try {
            // Remove any existing hold for this user
            AppointmentHold::releaseHold($user->id);

            // Create new hold (15 minutes)
            $hold = AppointmentHold::createHold(
                $user->id,
                $doctorId,
                $validated['appointment_date'],
                $validated['appointment_time'],
                session()->getId()
            );

            // Store in session for the wizard
            session([
                'appointment.date' => $validated['appointment_date'],
                'appointment.time' => $validated['appointment_time'],
                'appointment.timezone' => $timezone,
                'appointment.doctor_id' => $doctorId,
                'appointment.hold_id' => $hold->id,
                'appointment.hold_expires_at' => $hold->expires_at->toIso8601String(),
            ]);

            return redirect()->route('appointments.step2');

        } catch (\Exception $e) {
            Log::error('Failed to create appointment hold: ' . $e->getMessage());
            return back()->with('error', 'Este horario ya no esta disponible. Por favor seleccione otro.');
        }
    }

    /**
     * Hold a slot via AJAX (for real-time slot reservation)
     */
    public function holdSlot(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'date' => 'required|date|after:today',
            'time' => 'required|string',
            'doctor_id' => 'required|exists:doctors,id',
        ]);

        try {
            // Release any existing hold
            AppointmentHold::releaseHold($user->id);

            // Check if slot is still available
            $doctor = Doctor::findOrFail($validated['doctor_id']);
            $slots = $doctor->getAvailableSlotsForDate($validated['date']);
            $slot = collect($slots)->firstWhere('time_utc', $validated['time']);

            if (!$slot || !$slot['available']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este horario ya no esta disponible.',
                ], 409);
            }

            // Create hold
            $hold = AppointmentHold::createHold(
                $user->id,
                $validated['doctor_id'],
                $validated['date'],
                $validated['time'],
                session()->getId()
            );

            return response()->json([
                'success' => true,
                'hold_id' => $hold->id,
                'expires_at' => $hold->expires_at->toIso8601String(),
                'remaining_seconds' => $hold->remaining_seconds,
                'message' => 'Horario reservado por 15 minutos.',
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to hold slot: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al reservar el horario.',
            ], 500);
        }
    }

    /**
     * Release a slot hold via AJAX
     */
    public function releaseSlot(Request $request)
    {
        $user = Auth::user();

        try {
            AppointmentHold::releaseHold($user->id);

            // Clear session data
            session()->forget([
                'appointment.hold_id',
                'appointment.hold_expires_at',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reserva liberada.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al liberar la reserva.',
            ], 500);
        }
    }

    /**
     * Check hold status via AJAX
     */
    public function checkHoldStatus(Request $request)
    {
        $user = Auth::user();

        $hold = AppointmentHold::where('user_id', $user->id)->first();

        if (!$hold) {
            return response()->json([
                'active' => false,
                'message' => 'No tiene ninguna reserva activa.',
            ]);
        }

        if ($hold->isExpired()) {
            $hold->delete();
            session()->forget(['appointment.hold_id', 'appointment.hold_expires_at']);

            return response()->json([
                'active' => false,
                'expired' => true,
                'message' => 'Su reserva ha expirado.',
            ]);
        }

        return response()->json([
            'active' => true,
            'hold_id' => $hold->id,
            'expires_at' => $hold->expires_at->toIso8601String(),
            'remaining_seconds' => $hold->remaining_seconds,
            'remaining_time' => $hold->remaining_time,
        ]);
    }

    /**
     * Show Step 2 - File Upload
     */
    public function step2()
    {
        $user = Auth::user();

        // Check if step 1 is completed
        if (!session('appointment.date')) {
            return redirect()->route('appointments.step1')
                ->with('error', 'Por favor, seleccione una fecha y hora primero.');
        }

        // Get any previously uploaded documents for this user
        $documents = AppointmentDocument::where('user_id', $user->id)
            ->where('appointment_id', null) // Temporary uploads
            ->get();

        return view('appointments.step2', compact('user', 'documents'));
    }

    /**
     * Process file upload (AJAX)
     */
    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB max
            'document_type' => 'required|string|in:blood_test,chemistry,urine_test,chest_xray,ecg,vision_test,audiometry,other_medical',
        ]);

        $user = Auth::user();
        $file = $request->file('file');

        try {
            // Generate unique filename
            $filename = time() . '_' . $user->id . '_' . $file->getClientOriginalName();
            $path = 'appointments/documents/' . $user->id . '/' . $filename;

            // Upload to local storage
            Storage::disk('public')->put($path, file_get_contents($file));

            // Save document record
            $document = AppointmentDocument::create([
                'user_id' => $user->id,
                'document_type' => $request->document_type,
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'status' => 'uploaded',
            ]);

            // Log the upload
            AuditLog::logEvent(
                'document_uploaded',
                'success',
                [
                    'document_type' => $request->document_type,
                    'file_name' => $file->getClientOriginalName(),
                ],
                $user->id
            );

            return response()->json([
                'success' => true,
                'document' => $document,
                'message' => 'Archivo subido correctamente.',
            ]);

        } catch (\Exception $e) {
            Log::error('File upload failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al subir el archivo: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete uploaded file (AJAX)
     */
    public function deleteFile(Request $request, $id)
    {
        $user = Auth::user();
        $document = AppointmentDocument::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        try {
            // Delete from local storage
            Storage::disk('public')->delete($document->file_path);

            // Delete record
            $document->delete();

            return response()->json([
                'success' => true,
                'message' => 'Archivo eliminado correctamente.',
            ]);

        } catch (\Exception $e) {
            Log::error('File deletion failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el archivo.',
            ], 500);
        }
    }

    /**
     * Process Step 2 - Validate uploads and proceed
     */
    public function processStep2(Request $request)
    {
        $user = Auth::user();

        // Check required medical studies are uploaded
        $requiredTypes = [
            'blood_test',    // Biometria Hematica
            'chemistry',     // Quimica Sanguinea
            'urine_test',    // Examen General de Orina
            'chest_xray',    // Radiografia de Torax
            'ecg',           // Electrocardiograma
            'vision_test',   // Examen de Vista
            'audiometry',    // Audiometria
        ];

        $uploadedTypes = AppointmentDocument::where('user_id', $user->id)
            ->where('appointment_id', null)
            ->pluck('document_type')
            ->toArray();

        $missingTypes = array_diff($requiredTypes, $uploadedTypes);

        if (!empty($missingTypes)) {
            return back()->with('error', 'Por favor, suba todos los estudios medicos requeridos.');
        }

        session(['appointment.documents_uploaded' => true]);

        return redirect()->route('appointments.step3');
    }

    /**
     * Show Step 3 - Medical Declaration
     */
    public function step3()
    {
        $user = Auth::user();

        // Check previous steps
        if (!session('appointment.date')) {
            return redirect()->route('appointments.step1');
        }
        if (!session('appointment.documents_uploaded')) {
            return redirect()->route('appointments.step2');
        }

        return view('appointments.step3', compact('user'));
    }

    /**
     * Process Step 3 - Save medical declaration
     */
    public function processStep3(Request $request)
    {
        $validated = $request->validate([
            'exam_type' => 'required|string|in:new,renewal',
            'years_at_sea' => 'required|integer|min:0|max:60',
            'workplace_risks' => 'nullable|array',
            'workplace_risks.*' => 'string|in:none,noise,dust,radiation,other',
            'health_conditions' => 'nullable|array',
            'health_conditions.*' => 'string|in:high_blood_pressure,diabetes,hearing_vision,recent_surgeries',
            'additional_notes' => 'nullable|string|max:1000',
            'declaration_truthful' => 'required|accepted',
        ], [
            'exam_type.required' => 'Seleccione si es su primer examen o renovacion.',
            'years_at_sea.required' => 'Indique los anos de experiencia en el mar.',
            'declaration_truthful.required' => 'Debe confirmar que la informacion proporcionada es veridica.',
            'declaration_truthful.accepted' => 'Debe confirmar que la informacion proporcionada es veridica.',
        ]);

        // Store in session
        session(['appointment.medical_declaration' => $validated]);

        return redirect()->route('appointments.step4');
    }

    /**
     * Show Step 4 - Confirmation
     */
    public function step4()
    {
        $user = Auth::user();

        // Check all previous steps
        if (!session('appointment.date') || !session('appointment.documents_uploaded') || !session('appointment.medical_declaration')) {
            return redirect()->route('appointments.step1');
        }

        // Gather all appointment data
        $appointmentData = [
            'date' => session('appointment.date'),
            'time' => session('appointment.time'),
            'timezone' => session('appointment.timezone'),
            'medical_declaration' => session('appointment.medical_declaration'),
        ];

        // Get uploaded documents
        $documents = AppointmentDocument::where('user_id', $user->id)
            ->where('appointment_id', null)
            ->get();

        // Calculate service cost
        $serviceCost = $this->calculateServiceCost($appointmentData['medical_declaration']['exam_type']);

        return view('appointments.step4', compact('user', 'appointmentData', 'documents', 'serviceCost'));
    }

    /**
     * Process Step 4 - Create appointment and proceed to payment
     */
    public function processStep4(Request $request)
    {
        $user = Auth::user();

        // Get all session data
        $appointmentData = [
            'date' => session('appointment.date'),
            'time' => session('appointment.time'),
            'timezone' => session('appointment.timezone'),
            'medical_declaration' => session('appointment.medical_declaration'),
        ];

        // Calculate cost
        $serviceCost = $this->calculateServiceCost($appointmentData['medical_declaration']['exam_type']);

        // Determine health conditions from checkboxes
        $healthConditions = $appointmentData['medical_declaration']['health_conditions'] ?? [];
        $hasChronicConditions = in_array('high_blood_pressure', $healthConditions) || in_array('diabetes', $healthConditions);
        $hasSurgeries = in_array('recent_surgeries', $healthConditions);

        // Get doctor from session
        $doctorId = session('appointment.doctor_id');

        // Create the appointment
        $appointment = Appointment::create([
            'user_id' => $user->id,
            'doctor_id' => $doctorId,
            'appointment_date' => $appointmentData['date'],
            'appointment_time' => $appointmentData['time'],
            'timezone' => $appointmentData['timezone'],
            'exam_type' => $appointmentData['medical_declaration']['exam_type'],
            'years_at_sea' => $appointmentData['medical_declaration']['years_at_sea'],
            'current_position' => null, // Not collected in simplified form
            'vessel_type' => null, // Not collected in simplified form
            'has_chronic_conditions' => $hasChronicConditions,
            'chronic_conditions_detail' => $hasChronicConditions ? implode(', ', array_filter($healthConditions, fn($c) => in_array($c, ['high_blood_pressure', 'diabetes']))) : null,
            'takes_medications' => false, // Collected in additional_notes instead
            'medications_detail' => null,
            'has_allergies' => false, // Not collected separately
            'allergies_detail' => null,
            'has_surgeries' => $hasSurgeries,
            'surgeries_detail' => $hasSurgeries ? 'Cirugias recientes' : null,
            'workplace_risks' => $appointmentData['medical_declaration']['workplace_risks'] ?? [],
            'additional_notes' => $appointmentData['medical_declaration']['additional_notes'] ?? null,
            'declaration_truthful' => !empty($appointmentData['medical_declaration']['declaration_truthful']),
            'declaration_terms' => true, // Implied by proceeding
            'declaration_privacy' => true, // Implied by proceeding
            'declaration_consent' => true, // Implied by proceeding
            'subtotal' => $serviceCost['subtotal'],
            'tax' => $serviceCost['tax'],
            'total' => $serviceCost['total'],
            'status' => 'pending_payment',
        ]);

        // Link uploaded documents to this appointment
        AppointmentDocument::where('user_id', $user->id)
            ->where('appointment_id', null)
            ->update(['appointment_id' => $appointment->id]);

        // Release the hold since appointment is now created
        AppointmentHold::releaseHold($user->id);

        // Store appointment ID in session for payment
        session(['appointment.id' => $appointment->id]);
        session()->forget(['appointment.hold_id', 'appointment.hold_expires_at']);

        // Log the appointment creation
        try {
            AuditLog::logEvent(
                'appointment_created',
                'success',
                [
                    'appointment_id' => $appointment->id,
                    'date' => $appointment->appointment_date,
                    'exam_type' => $appointment->exam_type,
                ],
                $user->id
            );
        } catch (\Exception $e) {
            Log::warning('Failed to log appointment creation: ' . $e->getMessage());
        }

        return redirect()->route('appointments.step5');
    }

    /**
     * Show Step 5 - Payment
     */
    public function step5()
    {
        $user = Auth::user();

        $appointmentId = session('appointment.id');
        if (!$appointmentId) {
            return redirect()->route('appointments.step1');
        }

        $appointment = Appointment::findOrFail($appointmentId);

        return view('appointments.step5', compact('user', 'appointment'));
    }

    /**
     * Process payment (placeholder for payment gateway integration)
     */
    public function processPayment(Request $request)
    {
        $user = Auth::user();
        $appointmentId = session('appointment.id');

        if (!$appointmentId) {
            return response()->json(['success' => false, 'message' => 'No appointment found.'], 400);
        }

        $appointment = Appointment::findOrFail($appointmentId);

        // TODO: Integrate with actual payment gateway (Stripe/Mercado Pago)
        // For now, simulate successful payment

        $appointment->update([
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'payment_date' => now(),
            'payment_reference' => 'PAY-' . strtoupper(uniqid()),
        ]);

        // Clear session data
        session()->forget('appointment');

        // Log the payment
        try {
            AuditLog::logEvent(
                'appointment_paid',
                'success',
                [
                    'appointment_id' => $appointment->id,
                    'amount' => $appointment->total,
                    'payment_reference' => $appointment->payment_reference,
                ],
                $user->id
            );
        } catch (\Exception $e) {
            Log::warning('Failed to log payment: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Pago procesado correctamente.',
            'redirect' => route('appointments.success', $appointment->id),
        ]);
    }

    /**
     * Show success page after payment
     */
    public function success($id)
    {
        $user = Auth::user();
        $appointment = Appointment::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return view('appointments.success', compact('user', 'appointment'));
    }

    /**
     * Get available time slots based on doctor schedule
     */
    private function getAvailableSlots(?Doctor $doctor, string $userTimezone = 'America/Mexico_City')
    {
        $slots = [];
        $startDate = Carbon::tomorrow();

        // If no doctor, return empty (fallback to old behavior if needed)
        if (!$doctor) {
            return $this->getDefaultSlots($userTimezone);
        }

        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dateStr = $date->format('Y-m-d');

            // Get available slots from doctor model
            $daySlots = $doctor->getAvailableSlotsForDate($dateStr, $userTimezone);

            // Skip days with no available slots
            if (empty($daySlots)) {
                continue;
            }

            $slots[$dateStr] = [
                'date' => $dateStr,
                'display' => $date->locale('es')->isoFormat('dddd, D MMM'),
                'day_of_week' => $date->dayOfWeek,
                'slots' => $daySlots,
            ];
        }

        return $slots;
    }

    /**
     * Fallback: Get default slots when no doctor is configured
     */
    private function getDefaultSlots(string $userTimezone = 'America/Mexico_City')
    {
        $slots = [];
        $startDate = Carbon::tomorrow();

        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->copy()->addDays($i);

            // Skip Sundays
            if ($date->dayOfWeek === Carbon::SUNDAY) {
                continue;
            }

            $daySlots = [];
            $startHour = 9;
            $endHour = 17;

            for ($hour = $startHour; $hour < $endHour; $hour++) {
                $timeUtc = sprintf('%02d:00', $hour);

                // Convert to user timezone for display
                $utcTime = Carbon::parse($date->format('Y-m-d') . ' ' . $timeUtc, 'UTC');
                $userTime = $utcTime->copy()->setTimezone($userTimezone);

                $daySlots[] = [
                    'time_utc' => $timeUtc,
                    'time_display' => $userTime->format('H:i'),
                    'available' => true,
                    'booked' => 0,
                    'held' => 0,
                    'remaining' => 1,
                ];
            }

            $slots[$date->format('Y-m-d')] = [
                'date' => $date->format('Y-m-d'),
                'display' => $date->locale('es')->isoFormat('dddd, D MMM'),
                'day_of_week' => $date->dayOfWeek,
                'slots' => $daySlots,
            ];
        }

        return $slots;
    }

    /**
     * Calculate service cost
     */
    private function calculateServiceCost($examType)
    {
        $basePrice = $examType === 'new' ? 1500.00 : 1077.58;
        $taxRate = 0.16; // 16% IVA
        $tax = $basePrice * $taxRate;
        $total = $basePrice + $tax;

        return [
            'subtotal' => round($basePrice, 2),
            'tax' => round($tax, 2),
            'total' => round($total, 2),
            'tax_rate' => $taxRate * 100,
        ];
    }
}
