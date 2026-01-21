<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;

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

        $validated = $request->validate([
            'nombres' => 'nullable|string|max:100',
            'apellido_paterno' => 'nullable|string|max:100',
            'apellido_materno' => 'nullable|string|max:100',
            'curp' => 'nullable|string|size:18',
            'telefono_movil' => 'nullable|string|max:15',
            'fecha_nacimiento' => 'nullable|date',
            'sexo' => 'nullable|in:M,F',
            'nacionalidad' => 'nullable|string|max:50',
            'pais_nacimiento' => 'nullable|string|max:50',
            'calle' => 'nullable|string|max:200',
            'numero_exterior' => 'nullable|string|max:20',
            'numero_interior' => 'nullable|string|max:20',
            'codigo_postal' => 'nullable|string|max:5',
            'localidad' => 'nullable|string|max:100',
            'municipio' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:100',
        ], [
            'curp.size' => 'El CURP debe tener exactamente 18 caracteres.',
        ]);

        // Don't allow CURP update if already verified
        if ($user->curp_verification_status === 'verified' && isset($validated['curp'])) {
            unset($validated['curp']);
        }

        // Update user
        $user->update($validated);

        // Update name field from nombres + apellidos
        if ($validated['nombres'] || $validated['apellido_paterno']) {
            $name = trim(($validated['nombres'] ?? '') . ' ' . ($validated['apellido_paterno'] ?? ''));
            $user->update(['name' => $name]);
        }

        // Log the profile update
        try {
            AuditLog::logEvent(
                'profile_updated',
                AuditLog::STATUS_SUCCESS,
                ['user_id' => $user->id, 'fields_updated' => array_keys($validated)],
                $user->email
            );
        } catch (\Exception $e) {
            // Silently ignore logging errors
        }

        return redirect()->route('profile.show')->with('success', 'Perfil actualizado correctamente.');
    }
}
