<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
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

        // Check nationality to determine required fields
        $isMexican = $request->nacionalidad === 'mexicana';

        // Base validation rules
        $rules = [
            'nombres' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'nullable|string|max:100',
            'nacionalidad' => 'required|string|max:50',
            'telefono_movil' => 'required|string|max:15',
            'telefono_casa' => 'nullable|string|max:15',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|in:M,F',
            'pais_nacimiento' => 'required|string|max:50',
            'localidad' => 'nullable|string|max:100',
            'codigo_postal' => 'required|string|max:10',
            'calle' => 'required|string|max:200',
            'numero_exterior' => 'required|string|max:20',
            'numero_interior' => 'nullable|string|max:20',
            'libreta_de_mar' => 'nullable|string|max:50',
        ];

        // Conditional rules based on nationality
        if ($isMexican) {
            $rules['curp'] = 'required|string|size:18';
            $rules['rfc'] = 'required|string|min:12|max:13';
            $rules['estado'] = 'required|string|max:100';
            $rules['municipio'] = 'required|string|max:100';
            $rules['ine_document'] = 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120';
        } else {
            $rules['curp'] = 'nullable|string|size:18';
            $rules['rfc'] = 'nullable|string|min:12|max:13';
            $rules['estado_foreign'] = 'required|string|max:100';
            $rules['municipio_foreign'] = 'required|string|max:100';
            $rules['passport_document'] = 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120';
        }

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
            'curp.required' => 'El CURP es obligatorio para ciudadanos mexicanos.',
            'rfc.required' => 'El RFC es obligatorio para ciudadanos mexicanos.',
            'rfc.min' => 'El RFC debe tener al menos 12 caracteres.',
            'rfc.max' => 'El RFC no puede tener mas de 13 caracteres.',
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
            'estado.required' => 'El estado es obligatorio.',
            'municipio.required' => 'La ciudad es obligatoria.',
            'estado_foreign.required' => 'El estado/provincia es obligatorio.',
            'municipio_foreign.required' => 'La ciudad es obligatoria.',
            'parent_full_name.required' => 'El nombre del padre/madre/tutor es obligatorio para menores de edad.',
            'parent_email.required' => 'El correo del padre/madre/tutor es obligatorio para menores de edad.',
            'parent_relationship.required' => 'La relacion del padre/madre/tutor es obligatoria para menores de edad.',
            'ine_document.max' => 'El archivo INE no puede ser mayor a 5MB.',
            'ine_document.mimes' => 'El archivo INE debe ser JPG, PNG o PDF.',
            'passport_document.max' => 'El archivo de pasaporte no puede ser mayor a 5MB.',
            'passport_document.mimes' => 'El archivo de pasaporte debe ser JPG, PNG o PDF.',
        ];

        $validated = $request->validate($rules, $messages);

        // Prepare data for update
        $updateData = [
            'nombres' => $validated['nombres'],
            'apellido_paterno' => $validated['apellido_paterno'],
            'apellido_materno' => $validated['apellido_materno'] ?? null,
            'nacionalidad' => $validated['nacionalidad'],
            'telefono_movil' => $validated['telefono_movil'],
            'telefono_casa' => $validated['telefono_casa'] ?? null,
            'fecha_nacimiento' => $validated['fecha_nacimiento'],
            'sexo' => $validated['sexo'],
            'pais_nacimiento' => $validated['pais_nacimiento'],
            'localidad' => $validated['localidad'] ?? null,
            'codigo_postal' => $validated['codigo_postal'],
            'calle' => $validated['calle'],
            'numero_exterior' => $validated['numero_exterior'],
            'numero_interior' => $validated['numero_interior'] ?? null,
            'libreta_de_mar' => $validated['libreta_de_mar'] ?? null,
        ];

        // Handle Mexican-specific fields
        if ($isMexican) {
            $updateData['curp'] = $validated['curp'];
            $updateData['rfc'] = $validated['rfc'];
            $updateData['estado'] = $validated['estado'];
            $updateData['municipio'] = $validated['municipio'];
        } else {
            // For non-Mexican users, use the foreign address fields
            $updateData['curp'] = null;
            $updateData['rfc'] = null;
            $updateData['estado'] = $validated['estado_foreign'];
            $updateData['municipio'] = $validated['municipio_foreign'];
        }

        // Handle document upload to S3
        $documentUploaded = false;
        if ($isMexican && $request->hasFile('ine_document')) {
            $documentPath = $this->uploadDocumentToS3($request->file('ine_document'), $user->id, 'ine');
            if ($documentPath) {
                $updateData['document_path'] = $documentPath;
                $updateData['document_type'] = 'ine';
                $documentUploaded = true;
            }
        } elseif (!$isMexican && $request->hasFile('passport_document')) {
            $documentPath = $this->uploadDocumentToS3($request->file('passport_document'), $user->id, 'passport');
            if ($documentPath) {
                $updateData['document_path'] = $documentPath;
                $updateData['document_type'] = 'passport';
                $documentUploaded = true;
            }
        }

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

        // Update user
        $user->update($updateData);

        // Update name field from nombres + apellidos
        $name = trim(($updateData['nombres'] ?? '') . ' ' . ($updateData['apellido_paterno'] ?? ''));
        $user->update(['name' => $name]);

        // Handle parental consent for minors
        if ($isMinor && $parentalConsentData) {
            $this->handleParentalConsent($user, $parentalConsentData);
        }

        // Log the profile update
        try {
            AuditLog::logEvent(
                'profile_updated',
                AuditLog::STATUS_SUCCESS,
                [
                    'user_id' => $user->id,
                    'is_mexican' => $isMexican,
                    'is_minor' => $isMinor,
                    'document_uploaded' => $documentUploaded
                ],
                $user->email
            );
        } catch (\Exception $e) {
            // Silently ignore logging errors
        }

        $successMessage = 'Perfil actualizado correctamente.';
        if ($documentUploaded) {
            $successMessage .= ' Documento subido exitosamente.';
        }
        if ($isMinor) {
            $successMessage .= ' Se ha enviado una solicitud de consentimiento parental al correo proporcionado.';
        }

        return redirect()->route('profile.show')->with('success', $successMessage);
    }

    /**
     * Upload document to S3
     */
    private function uploadDocumentToS3($file, $userId, $documentType): ?string
    {
        try {
            $extension = $file->getClientOriginalExtension();
            $filename = $documentType . '_' . $userId . '_' . time() . '.' . $extension;
            $path = 'documents/' . $userId . '/' . $filename;

            // Upload to S3
            Storage::disk('s3')->put($path, file_get_contents($file), 'private');

            return $path;
        } catch (\Exception $e) {
            \Log::error('Failed to upload document to S3: ' . $e->getMessage());
            return null;
        }
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

    /**
     * Update user's profile photo
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'profile_photo.required' => 'Debe seleccionar una imagen.',
            'profile_photo.image' => 'El archivo debe ser una imagen.',
            'profile_photo.mimes' => 'La imagen debe ser JPG o PNG.',
            'profile_photo.max' => 'La imagen no puede ser mayor a 2MB.',
        ]);

        $user = Auth::user();

        try {
            $file = $request->file('profile_photo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $extension;
            $path = 'profile-photos/' . $filename;

            // Delete old photo if exists
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            // Upload new photo to local public storage
            Storage::disk('public')->put($path, file_get_contents($file));

            // Update user profile_photo path
            $user->update(['profile_photo' => $path]);

            // Log the update
            try {
                AuditLog::logEvent(
                    'profile_photo_updated',
                    AuditLog::STATUS_SUCCESS,
                    ['user_id' => $user->id],
                    $user->email
                );
            } catch (\Exception $e) {
                // Silently ignore logging errors
            }

            // Generate public URL
            $photoUrl = asset('storage/' . $path);

            return response()->json([
                'success' => true,
                'message' => 'Foto de perfil actualizada correctamente.',
                'photo_url' => $photoUrl
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to upload profile photo: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al subir la foto. Por favor intente de nuevo.'
            ], 500);
        }
    }

    /**
     * Update user's password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'La contrasena actual es obligatoria.',
            'new_password.required' => 'La nueva contrasena es obligatoria.',
            'new_password.min' => 'La nueva contrasena debe tener al menos 8 caracteres.',
            'new_password.confirmed' => 'Las contrasenas no coinciden.',
        ]);

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'La contrasena actual es incorrecta.'
            ], 422);
        }

        // Check if new password is same as current
        if (Hash::check($request->new_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'La nueva contrasena debe ser diferente a la actual.'
            ], 422);
        }

        try {
            // Update password
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            // Log the update
            try {
                AuditLog::logEvent(
                    'password_changed',
                    AuditLog::STATUS_SUCCESS,
                    ['user_id' => $user->id],
                    $user->email
                );
            } catch (\Exception $e) {
                // Silently ignore logging errors
            }

            return response()->json([
                'success' => true,
                'message' => 'Contrasena actualizada correctamente.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to update password: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la contrasena. Por favor intente de nuevo.'
            ], 500);
        }
    }
}
