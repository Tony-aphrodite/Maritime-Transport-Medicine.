<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;
use App\Models\ParentalConsent;

class UserProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Check if user is a minor (for parental consent validation)
        $isMinor = false;
        if ($request->fecha_nacimiento) {
            $birthDate = \Carbon\Carbon::parse($request->fecha_nacimiento);
            $age = $birthDate->age;
            $isMinor = $age < 18;
        }

        // Base validation rules
        $rules = [
            'nombres' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'nullable|string|max:100',
            'curp' => 'required|string|size:18',
            'rfc' => 'nullable|string|max:13',
            'telefono_movil' => 'required|string|max:15',
            'telefono_casa' => 'nullable|string|max:15',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|in:M,F',
            'nacionalidad' => 'required|string|max:50',
            'pais_nacimiento' => 'required|string|max:50',
            'calle' => 'required|string|max:200',
            'numero_exterior' => 'required|string|max:20',
            'numero_interior' => 'nullable|string|max:20',
            'codigo_postal' => 'required|string|max:5',
            'localidad' => 'required|string|max:100',
            'municipio' => 'required|string|max:100',
            'estado' => 'required|string|max:100',
            'face_verified' => 'nullable|string',
        ];

        // Add parental consent validation for minors
        if ($isMinor) {
            $rules['parent_full_name'] = 'required|string|max:200';
            $rules['parent_email'] = 'required|email|max:255';
            $rules['parent_phone'] = 'nullable|string|max:15';
            $rules['parent_relationship'] = 'required|string|in:padre,madre,tutor_legal,abuelo,otro';
        } else {
            $rules['parent_full_name'] = 'nullable|string|max:200';
            $rules['parent_email'] = 'nullable|email|max:255';
            $rules['parent_phone'] = 'nullable|string|max:15';
            $rules['parent_relationship'] = 'nullable|string|in:padre,madre,tutor_legal,abuelo,otro';
        }

        $messages = [
            'curp.size' => 'El CURP debe tener exactamente 18 caracteres.',
            'curp.required' => 'El CURP es obligatorio.',
            'nombres.required' => 'El nombre es obligatorio.',
            'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
            'telefono_movil.required' => 'El telefono movil es obligatorio.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'sexo.required' => 'El sexo es obligatorio.',
            'nacionalidad.required' => 'La nacionalidad es obligatoria.',
            'pais_nacimiento.required' => 'El pais de nacimiento es obligatorio.',
            'calle.required' => 'La calle es obligatoria.',
            'numero_exterior.required' => 'El numero exterior es obligatorio.',
            'codigo_postal.required' => 'El codigo postal es obligatorio.',
            'localidad.required' => 'La colonia/localidad es obligatoria.',
            'municipio.required' => 'El municipio es obligatorio.',
            'estado.required' => 'El estado es obligatorio.',
            'parent_full_name.required' => 'El nombre del padre/madre/tutor es obligatorio para menores de edad.',
            'parent_email.required' => 'El correo del padre/madre/tutor es obligatorio para menores de edad.',
            'parent_relationship.required' => 'La relacion del padre/madre/tutor es obligatoria para menores de edad.',
        ];

        $validated = $request->validate($rules, $messages);

        // Don't allow CURP update if already verified
        if ($user->curp_verification_status === 'verified' && isset($validated['curp'])) {
            unset($validated['curp']);
        }

        // Handle face verification status
        if ($request->face_verified === 'true' && $user->face_verification_status !== 'verified') {
            $validated['face_verification_status'] = 'verified';
        }

        // Remove face_verified from validated data (not a database field)
        unset($validated['face_verified']);

        // Extract parental consent fields (they are not stored in users table)
        $parentalConsentData = null;
        if ($isMinor && !empty($validated['parent_full_name']) && !empty($validated['parent_email'])) {
            $parentalConsentData = [
                'parent_full_name' => $validated['parent_full_name'],
                'parent_email' => $validated['parent_email'],
                'parent_phone' => $validated['parent_phone'] ?? null,
                'parent_relationship' => $validated['parent_relationship'] ?? 'parent',
            ];
        }

        // Remove parental consent fields from validated data (not in users table)
        unset($validated['parent_full_name']);
        unset($validated['parent_email']);
        unset($validated['parent_phone']);
        unset($validated['parent_relationship']);

        // Update user
        $user->update($validated);

        // Update name field from nombres + apellidos
        if (isset($validated['nombres']) || isset($validated['apellido_paterno'])) {
            $name = trim(($validated['nombres'] ?? $user->nombres ?? '') . ' ' . ($validated['apellido_paterno'] ?? $user->apellido_paterno ?? ''));
            $user->update(['name' => $name]);
        }

        // Handle parental consent for minors
        if ($isMinor && $parentalConsentData) {
            $this->handleParentalConsent($user, $parentalConsentData);
        }

        // Log the profile update
        try {
            AuditLog::logEvent(
                'profile_updated',
                AuditLog::STATUS_SUCCESS,
                ['user_id' => $user->id, 'fields_updated' => array_keys($validated), 'is_minor' => $isMinor],
                $user->email
            );
        } catch (\Exception $e) {
            // Silently ignore logging errors
        }

        $successMessage = 'Perfil actualizado correctamente.';
        if ($isMinor) {
            $successMessage .= ' Se ha enviado una solicitud de consentimiento parental al correo proporcionado.';
        }

        return redirect()->route('profile.show')->with('success', $successMessage);
    }

    /**
     * Handle parental consent request for minors
     */
    private function handleParentalConsent($user, array $parentalConsentData): void
    {
        // Check if there's already a pending or approved consent
        $existingConsent = ParentalConsent::where('minor_email', $user->email)
            ->whereIn('status', [ParentalConsent::STATUS_PENDING, ParentalConsent::STATUS_APPROVED])
            ->where('expires_at', '>', now())
            ->first();

        if ($existingConsent) {
            // Update existing consent if parent info changed
            if ($existingConsent->status === ParentalConsent::STATUS_PENDING) {
                $existingConsent->update([
                    'parent_full_name' => $parentalConsentData['parent_full_name'],
                    'parent_email' => $parentalConsentData['parent_email'],
                    'parent_phone' => $parentalConsentData['parent_phone'],
                    'relationship' => $this->mapRelationship($parentalConsentData['parent_relationship']),
                ]);
            }
            return;
        }

        // Create new parental consent request
        try {
            ParentalConsent::createConsentRequest([
                'minor_email' => $user->email,
                'minor_full_name' => $user->full_name,
                'minor_birth_date' => $user->fecha_nacimiento,
                'parent_full_name' => $parentalConsentData['parent_full_name'],
                'parent_email' => $parentalConsentData['parent_email'],
                'parent_phone' => $parentalConsentData['parent_phone'],
                'relationship' => $this->mapRelationship($parentalConsentData['parent_relationship']),
            ]);
        } catch (\Exception $e) {
            // Log error but don't fail the profile update
            \Log::error('Failed to create parental consent request: ' . $e->getMessage());
        }
    }

    /**
     * Map form relationship value to database enum value
     */
    private function mapRelationship(string $relationship): string
    {
        $map = [
            'padre' => 'parent',
            'madre' => 'parent',
            'tutor_legal' => 'tutor',
            'abuelo' => 'guardian',
            'otro' => 'guardian',
        ];

        return $map[$relationship] ?? 'parent';
    }
}
