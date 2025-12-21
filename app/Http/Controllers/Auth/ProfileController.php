<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Show the profile completion form
     */
    public function showCompleteForm()
    {
        $user = Auth::user();

        // If profile is already complete, redirect to dashboard
        if ($user->hasCompletedProfile()) {
            return redirect()->route('dashboard')
                ->with('info', 'Su perfil ya está completo.');
        }

        return view('auth.complete-profile');
    }

    /**
     * Handle profile completion
     */
    public function complete(Request $request)
    {
        $validated = $request->validate([
            'curp' => 'required|size:18|unique:users,curp,' . Auth::id(),
            'rfc' => 'nullable|string|max:13',
            'nombres' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'telefono_movil' => 'required|string|max:20',
            'nacionalidad' => 'required|string|max:100',
            'sexo' => 'required|in:masculino,femenino',
            'fecha_nacimiento' => 'required|date',
            'pais_nacimiento' => 'required|string|max:100',
            'estado_nacimiento' => 'required|string|max:100',
            'estado' => 'required|string|max:100',
            'municipio' => 'required|string|max:255',
            'localidad' => 'required|string|max:255',
            'codigo_postal' => 'required|string|size:5',
            'calle' => 'required|string|max:255',
            'numero_exterior' => 'required|string|max:20',
            'numero_interior' => 'nullable|string|max:20',
        ], [
            'curp.required' => 'El CURP es obligatorio.',
            'curp.size' => 'El CURP debe tener exactamente 18 caracteres.',
            'curp.unique' => 'Este CURP ya está registrado.',
            'nombres.required' => 'El nombre es obligatorio.',
            'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
            'telefono_movil.required' => 'El teléfono móvil es obligatorio.',
            'nacionalidad.required' => 'La nacionalidad es obligatoria.',
            'sexo.required' => 'El sexo es obligatorio.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'pais_nacimiento.required' => 'El país de nacimiento es obligatorio.',
            'estado_nacimiento.required' => 'El estado de nacimiento es obligatorio.',
            'estado.required' => 'El estado es obligatorio.',
            'municipio.required' => 'El municipio es obligatorio.',
            'localidad.required' => 'La localidad es obligatoria.',
            'codigo_postal.required' => 'El código postal es obligatorio.',
            'codigo_postal.size' => 'El código postal debe tener 5 dígitos.',
            'calle.required' => 'La calle es obligatoria.',
            'numero_exterior.required' => 'El número exterior es obligatorio.',
        ]);

        try {
            $user = Auth::user();

            // Check if face verification was completed
            $faceVerified = $request->input('face_verified') === 'true';
            $faceConfidence = $request->input('face_verification_confidence');

            if (!$faceVerified) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['face_verification' => 'Debe completar la verificación facial antes de enviar el registro.']);
            }

            // Update user profile
            $user->update([
                'curp' => strtoupper($validated['curp']),
                'rfc' => $validated['rfc'] ? strtoupper($validated['rfc']) : null,
                'nombres' => $validated['nombres'],
                'apellido_paterno' => $validated['apellido_paterno'],
                'apellido_materno' => $validated['apellido_materno'],
                'telefono_movil' => $validated['telefono_movil'],
                'nacionalidad' => $validated['nacionalidad'],
                'sexo' => $validated['sexo'],
                'fecha_nacimiento' => $validated['fecha_nacimiento'],
                'pais_nacimiento' => $validated['pais_nacimiento'],
                'estado_nacimiento' => $validated['estado_nacimiento'],
                'estado' => $validated['estado'],
                'municipio' => $validated['municipio'],
                'localidad' => $validated['localidad'],
                'codigo_postal' => $validated['codigo_postal'],
                'calle' => $validated['calle'],
                'numero_exterior' => $validated['numero_exterior'],
                'numero_interior' => $validated['numero_interior'],
                'curp_verification_status' => 'verified',
                'face_verification_status' => 'verified',
                'curp_verified_at' => now(),
                'face_verified_at' => now(),
                'face_verification_confidence' => $faceConfidence ? floatval($faceConfidence) : null,
                'profile_completed' => true,
                'account_status' => 'active',
            ]);

            // Log the profile completion
            try {
                AuditLog::logEvent(
                    'profile_completed',
                    'success',
                    [
                        'email' => $user->email,
                        'curp' => $validated['curp'],
                        'nombres' => $validated['nombres'],
                    ],
                    $user->id
                );
            } catch (\Exception $e) {
                Log::warning('Failed to log profile completion: ' . $e->getMessage());
            }

            return redirect()->route('dashboard')
                ->with('success', '¡Perfil completado exitosamente! Bienvenido a Maritime Transport Medicine.');

        } catch (\Exception $e) {
            Log::error('Profile completion error: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Error al completar el perfil. Por favor intente nuevamente.']);
        }
    }
}
