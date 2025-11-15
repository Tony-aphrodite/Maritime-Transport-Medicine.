<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
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
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|confirmed',
                'curp' => 'required|size:18',
                'nombres' => 'required|string|max:255',
                'apellido_paterno' => 'required|string|max:255',
                'apellido_materno' => 'nullable|string|max:255',
                'telefono_movil' => 'required|string|max:20',
                'nacionalidad' => 'required|string',
                'sexo' => 'required|in:masculino,femenino',
                'fecha_nacimiento' => 'required|date',
                'pais_nacimiento' => 'required|string',
                'estado_nacimiento' => 'required|string',
                'estado' => 'required|string',
                'municipio' => 'required|string',
                'localidad' => 'required|string',
                'codigo_postal' => 'required|string|size:5',
                'calle' => 'required|string|max:255',
                'numero_exterior' => 'required|string|max:20',
                'numero_interior' => 'nullable|string|max:20',
            ]);

            // Check if face verification was completed (check both form input and query parameters)
            $faceVerified = $request->input('face_verified') === 'true' || $request->query('face_verified') === 'true';
            
            // Log the verification check for debugging
            \Log::info('Registration face verification check:', [
                'form_face_verified' => $request->input('face_verified'),
                'query_face_verified' => $request->query('face_verified'),
                'final_face_verified' => $faceVerified,
                'all_form_data' => $request->all()
            ]);
            
            if (!$faceVerified) {
                \Log::warning('Registration blocked - face verification not completed');
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['face_verification' => 'Debe completar la verificación facial antes de enviar el registro.']);
            }

            // Create user account (in a real application, you would save to database)
            // For now, we'll simulate successful registration
            $userData = [
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'curp' => $validated['curp'],
                'nombres' => $validated['nombres'],
                'apellido_paterno' => $validated['apellido_paterno'],
                'apellido_materno' => $validated['apellido_materno'],
                'telefono_movil' => $validated['telefono_movil'],
                'nacionalidad' => $validated['nacionalidad'],
                'sexo' => $validated['sexo'],
                'fecha_nacimiento' => $validated['fecha_nacimiento'],
                'pais_nacimiento' => $validated['pais_nacimiento'],
                'estado_nacimiento' => $validated['estado_nacimiento'],
                'direccion' => [
                    'estado' => $validated['estado'],
                    'municipio' => $validated['municipio'],
                    'localidad' => $validated['localidad'],
                    'codigo_postal' => $validated['codigo_postal'],
                    'calle' => $validated['calle'],
                    'numero_exterior' => $validated['numero_exterior'],
                    'numero_interior' => $validated['numero_interior'],
                ],
                'face_verified' => true,
                'face_verification_confidence' => $request->input('face_verification_confidence') ?: $request->query('confidence', '95'),
                'created_at' => now(),
            ];

            // Log successful registration
            try {
                AuditLog::logEvent(
                    AuditLog::EVENT_ACCOUNT_CREATED,
                    AuditLog::STATUS_SUCCESS,
                    [
                        'registration_method' => 'full_form',
                        'face_verification_completed' => true,
                        'face_verification_confidence' => $request->input('face_verification_confidence') ?: $request->query('confidence', '95'),
                        'curp_provided' => !empty($validated['curp']),
                        'email' => $validated['email'],
                        'nombres' => $validated['nombres'],
                        'apellido_paterno' => $validated['apellido_paterno'],
                    ],
                    $validated['email']
                );
            } catch (\Exception $e) {
                Log::warning('Failed to log registration success: ' . $e->getMessage());
            }

            // Store user data in session (simulate successful registration)
            Session::put('registered_user', $userData);
            Session::put('registration_success', true);

            // Redirect to success page
            return redirect('/login')->with('registration_success', 
                'Registro completado exitosamente. Ya puede iniciar sesión con sus credenciales.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation failure
            try {
                AuditLog::logEvent(
                    AuditLog::EVENT_ACCOUNT_CREATION_FAILURE,
                    AuditLog::STATUS_FAILURE,
                    [
                        'registration_method' => 'full_form',
                        'validation_errors' => $e->errors(),
                        'email' => $request->input('email'),
                    ],
                    $request->input('email', 'unknown')
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
                        'registration_method' => 'full_form',
                        'error' => $e->getMessage(),
                        'email' => $request->input('email'),
                    ],
                    $request->input('email', 'unknown')
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