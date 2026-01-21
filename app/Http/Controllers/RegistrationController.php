<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\AuditLog;

class RegistrationController extends Controller
{
    /**
     * Show registration form
     */
    public function showRegistrationForm()
    {
        return view('registro');
    }

    /**
     * Process registration form submission
     */
    public function processRegistration(Request $request)
    {
        try {
            // Validate the incoming request
            $validated = $request->validate([
                'curp' => 'required|size:18|unique:users,curp',
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
            ]);

            // Check if face verification was completed
            $faceVerified = $request->input('face_verified') === 'true' || $request->query('face_verified') === 'true';
            $faceConfidence = $request->input('face_verification_confidence') ?: $request->query('confidence', null);

            Log::info('Registration face verification check:', [
                'form_face_verified' => $request->input('face_verified'),
                'query_face_verified' => $request->query('face_verified'),
                'final_face_verified' => $faceVerified,
                'confidence' => $faceConfidence
            ]);

            if (!$faceVerified) {
                Log::warning('Registration blocked - face verification not completed');
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['face_verification' => 'Debe completar la verificación facial antes de enviar el registro.']);
            }

            // Create user in database with email/password for authentication
            $curp = strtoupper($validated['curp']);
            $email = $curp . '@sistema.gob.mx'; // Create unique email from CURP
            $defaultPassword = bcrypt($curp); // Use CURP as default password (can be changed later)
            
            $user = User::create([
                'curp' => $curp,
                'email' => $email,
                'password' => $defaultPassword,
                'email_verified_at' => now(), // Mark as verified since this is complete verification flow
                'profile_completed' => true, // Mark profile as completed since all data is provided
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
                'account_status' => 'active',
                'curp_verified_at' => now(),
                'face_verified_at' => now(),
                'face_verification_confidence' => $faceConfidence ? floatval($faceConfidence) : null,
                'registration_ip' => $request->ip(),
                'registration_user_agent' => $request->userAgent(),
            ]);

            // Log successful registration
            try {
                AuditLog::logEvent(
                    AuditLog::EVENT_ACCOUNT_CREATED,
                    AuditLog::STATUS_SUCCESS,
                    [
                        'registration_method' => 'curp_face_verification',
                        'face_verification_completed' => true,
                        'face_verification_confidence' => $faceConfidence,
                        'curp' => $validated['curp'],
                        'nombres' => $validated['nombres'],
                        'apellido_paterno' => $validated['apellido_paterno'],
                    ],
                    $validated['curp']
                );
            } catch (\Exception $e) {
                Log::warning('Failed to log registration success: ' . $e->getMessage());
            }

            // Store success in session
            Session::put('registration_success', true);
            Session::put('registered_curp', $user->curp);

            // Auto-login the user
            Auth::login($user);

            // Redirect to dashboard since this is the legacy flow with all verification completed
            return redirect('/dashboard')->with('success',
                'Registro completado exitosamente. Bienvenido al sistema.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation failure
            try {
                AuditLog::logEvent(
                    AuditLog::EVENT_ACCOUNT_CREATION_FAILURE,
                    AuditLog::STATUS_FAILURE,
                    [
                        'registration_method' => 'curp_face_verification',
                        'validation_errors' => $e->errors(),
                        'curp' => $request->input('curp'),
                    ],
                    $request->input('curp', 'unknown')
                );
            } catch (\Exception $logError) {
                Log::warning('Failed to log registration failure: ' . $logError->getMessage());
            }

            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors());

        } catch (\Exception $e) {
            // Log general error
            try {
                AuditLog::logEvent(
                    AuditLog::EVENT_ACCOUNT_CREATION_FAILURE,
                    AuditLog::STATUS_FAILURE,
                    [
                        'registration_method' => 'curp_face_verification',
                        'error' => $e->getMessage(),
                        'curp' => $request->input('curp'),
                    ],
                    $request->input('curp', 'unknown')
                );
            } catch (\Exception $logError) {
                Log::warning('Failed to log registration error: ' . $logError->getMessage());
            }

            Log::error('Registration error: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->withErrors(['general' => 'Error al procesar el registro. Por favor intente nuevamente.']);
        }
    }
}
