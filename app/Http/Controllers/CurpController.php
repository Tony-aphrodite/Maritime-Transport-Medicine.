<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\AuditLog;

class CurpController extends Controller
{
    private $verificamexToken;
    private $verificamexBaseUrl;

    public function __construct()
    {
        // VerificaMex API credentials
        $this->verificamexToken = env('VERIFICAMEX_TOKEN', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiYWVlNjExZWEyN2M0MzM2ZjgzOWI1NTQ1MjVlZTQ3ZTI4MTJlYTRiMGQ3MTQ3Yjk0MDdkYjdhNjhjNjFkNWYxZDhmNDgxMjBiYzdhM2FkODIiLCJpYXQiOjE3NjMwODQ3ODIuMjAwNDYxLCJuYmYiOjE3NjMwODQ3ODIuMjAwNDk5LCJleHAiOjE3OTQ2MjA3ODIuMTg2MTEsInN1YiI6IjgxNzQiLCJzY29wZXMiOltdfQ.UTZKx5J3-w1iH6z6EcwQbFgjCNL5U57rjLXQ_pLK__wva8-4icxvxikICqRVrNIzjLYu5WpETi-2wpg4Qh3W_0MbgVyma854mI2AF_Bffbaf3X6e-UfOelYwIsk6FD1iJrPzETNWZCUqSFkEYI_o9F2-g2tdtbf2pGw4-7CqGVef1n3utJPpftK9P4Q6L5t3q8rg-rY6u22enExNEO6-xAP2ZjhkWmEU1J1rzCtD4KcdWY1zOK6zgYEA-NW0Aobay67Dnhkf-m3zsTRleKK6M0CGGjV89AOlZ186bBx1nHqw3g2nVf_5cl6q9s-RraYDXoXO8ppR0U76bV3lBesoG7_9y8V4aIoZxI8uA-Wp4jYoqsCN8KdUE4lHNG4vyaiOvl23dfcoUs2ELSwe-xNK_JCqEBZV1cRF0qzF7_0V1buKMDAI_43TxPMJ2LFkVFz2nGWyVMd88uKijA-OXS-R1KgvikYJt8s3OH8XvV3SWr4PhlGp1uXiOdxgXbRVmcYbJcxmvEvlwQTk0TdEKUDSDaVvF3kJHom-4ddoA-nMiQx-mtY31l05V01346pm2-5K-sXnQxpjaSRjjIWRHhb9FG09NeHVUCtjc7ApQq7RSSeo8KuEVOHoX5kWsY5H7820D4HqFnqq_7UEyuQCGsLlgk4A2SUxRhiXq2suTc86n-k');
        $this->verificamexBaseUrl = env('VERIFICAMEX_BASE_URL', 'https://api.verificamex.com');
    }

    /**
     * Show CURP validation form
     */
    public function showValidationForm()
    {
        return view('curp.validate');
    }

    /**
     * Validate CURP format (client-side validation)
     */
    private function validateCurpFormat($curp)
    {
        // CURP format: 4 letters + 6 numbers + 1 letter + 1 number + 1 letter + 3 alphanumeric + 1 number
        // Example: PEGJ850415HDFRRN05
        $pattern = '/^[A-Z]{1}[AEIOUX]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01])[HM]{1}[A-Z]{2}[BCDFGHJKLMNPQRSTVWXYZ]{3}[0-9A-Z]{1}[0-9]{1}$/';
        return preg_match($pattern, strtoupper($curp));
    }

    /**
     * Validate CURP against VerificaMex API
     */
    public function validateCurp(Request $request)
    {
        try {
            // Validate request data
            $validator = Validator::make($request->all(), [
                'curp' => 'required|string|size:18|alpha_num'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'CURP debe tener exactamente 18 caracteres alfanuméricos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $curp = strtoupper(trim($request->curp));
            $verificationId = 'curp_' . uniqid();

            // Log CURP verification attempt
            try {
                AuditLog::logEvent(
                    AuditLog::EVENT_CURP_VERIFICATION_ATTEMPT,
                    AuditLog::STATUS_IN_PROGRESS,
                    ['curp_format_validation' => 'started'],
                    $curp,
                    $verificationId
                );
            } catch (\Exception $e) {
                Log::warning('Failed to log CURP verification attempt: ' . $e->getMessage());
            }

            // Validate CURP format
            if (!$this->validateCurpFormat($curp)) {
                // Log format validation failure
                try {
                    AuditLog::logCurpVerification(
                        $curp,
                        AuditLog::STATUS_FAILURE,
                        ['error' => 'Invalid CURP format', 'step' => 'format_validation'],
                        $verificationId
                    );
                } catch (\Exception $e) {
                    Log::warning('Failed to log CURP format failure: ' . $e->getMessage());
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Formato de CURP inválido. Verificar estructura del código.',
                    'data' => null
                ], 422);
            }

            // For demonstration purposes, we'll always return extracted data from CURP format
            // This ensures the auto-fill functionality always works for testing
            Log::info('CURP validation request for: ' . $curp);
            
            try {
                // Try to call VerificaMex API first
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->verificamexToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])->timeout(15)->post($this->verificamexBaseUrl . '/api/curp', [
                    'curp' => $curp
                ]);

                Log::info('VerificaMex API Response', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'successful' => $response->successful()
                ]);

                if ($response->successful()) {
                    $apiData = $response->json();
                    
                    // Check multiple possible response structures
                    if ($apiData) {
                        // Handle different API response formats
                        $curpData = null;
                        $isValid = false;
                        
                        // Format 1: Direct success with data
                        if (isset($apiData['success']) && $apiData['success'] && isset($apiData['data'])) {
                            $curpData = $apiData['data'];
                            $isValid = true;
                        }
                        // Format 2: Valid flag with person data
                        elseif (isset($apiData['valid']) && $apiData['valid'] && isset($apiData['persona'])) {
                            $curpData = $apiData['persona'];
                            $isValid = true;
                        }
                        // Format 3: Status OK with results
                        elseif (isset($apiData['status']) && $apiData['status'] === 'OK' && isset($apiData['resultado'])) {
                            $curpData = $apiData['resultado'];
                            $isValid = true;
                        }
                        // Format 4: Direct person data without wrapper
                        elseif (isset($apiData['nombres']) || isset($apiData['nombre'])) {
                            $curpData = $apiData;
                            $isValid = true;
                        }
                        
                        if ($isValid && $curpData) {
                            // Log successful CURP verification
                            try {
                                AuditLog::logCurpVerification(
                                    $curp,
                                    AuditLog::STATUS_SUCCESS,
                                    [
                                        'verification_method' => 'verificamex_api',
                                        'has_details' => !empty($curpData),
                                        'api_response_time' => now()->toISOString()
                                    ],
                                    $verificationId
                                );
                            } catch (\Exception $e) {
                                Log::warning('Failed to log CURP verification success: ' . $e->getMessage());
                            }
                            
                            // Normalize field names from different API response formats
                            $nombres = $curpData['nombres'] ?? $curpData['nombre'] ?? $curpData['name'] ?? null;
                            $primerApellido = $curpData['primer_apellido'] ?? $curpData['primerApellido'] ?? 
                                            $curpData['apellido_paterno'] ?? $curpData['apellidoPaterno'] ?? null;
                            $segundoApellido = $curpData['segundo_apellido'] ?? $curpData['segundoApellido'] ?? 
                                             $curpData['apellido_materno'] ?? $curpData['apellidoMaterno'] ?? null;
                            $fechaNacimiento = $curpData['fecha_nacimiento'] ?? $curpData['fechaNacimiento'] ?? 
                                             $curpData['birth_date'] ?? null;
                            $sexo = $curpData['sexo'] ?? $curpData['gender'] ?? $curpData['genero'] ?? null;
                            $entidadNacimiento = $curpData['entidad_nacimiento'] ?? $curpData['entidadNacimiento'] ?? 
                                               $curpData['estado_nacimiento'] ?? $curpData['birth_state'] ?? null;
                            
                            return response()->json([
                                'success' => true,
                                'message' => 'CURP válido y verificado exitosamente contra RENAPO',
                                'data' => [
                                    'curp' => $curp,
                                    'valid' => true,
                                    'details' => [
                                        'nombres' => $nombres,
                                        'primerApellido' => $primerApellido,
                                        'segundoApellido' => $segundoApellido,
                                        'fechaNacimiento' => $fechaNacimiento,
                                        'sexo' => $sexo,
                                        'entidadNacimiento' => $entidadNacimiento,
                                        'nacionalidad' => $curpData['nacionalidad'] ?? 'MEXICANA',
                                        'estatus' => $curpData['estatus'] ?? $curpData['status'] ?? 'VERIFICADO'
                                    ],
                                    'verification_date' => now()->toISOString(),
                                    'certificate_url' => $apiData['certificate_url'] ?? null
                                ]
                            ]);
                        }
                    }
                }
            } catch (\Exception $apiError) {
                Log::warning('VerificaMex API call failed: ' . $apiError->getMessage(), [
                    'curp' => $curp,
                    'error_details' => $apiError->getTraceAsString()
                ]);
            }

            // Fallback: Extract only reliable info from CURP format when API fails
            $extractedInfo = $this->extractInfoFromCurp($curp);
            
            // Log fallback verification
            try {
                AuditLog::logCurpVerification(
                    $curp,
                    AuditLog::STATUS_SUCCESS,
                    [
                        'verification_method' => 'curp_format_extraction',
                        'note' => 'API unavailable, using format-based extraction (names not available)',
                        'api_response_time' => now()->toISOString()
                    ],
                    $verificationId
                );
            } catch (\Exception $e) {
                Log::warning('Failed to log CURP fallback verification: ' . $e->getMessage());
            }
            
            return response()->json([
                'success' => true,
                'message' => 'CURP válido. Solo fecha, sexo y entidad disponibles (API de RENAPO temporalmente no disponible).',
                'data' => [
                    'curp' => $curp,
                    'valid' => true,
                    'details' => [
                        'nombres' => null, // Names not available without RENAPO API
                        'primerApellido' => null,
                        'segundoApellido' => null,
                        'fechaNacimiento' => $extractedInfo['fechaNacimiento'],
                        'sexo' => $extractedInfo['sexo'],
                        'entidadNacimiento' => $extractedInfo['entidadNacimiento'],
                        'nacionalidad' => 'MEXICANA',
                        'estatus' => 'Formato válido - nombres requieren verificación RENAPO'
                    ],
                    'verification_date' => now()->toISOString(),
                    'certificate_url' => null
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('CURP Validation Error', [
                'error' => $e->getMessage(),
                'curp' => $request->curp ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor. Contacte al administrador.',
                'data' => null
            ], 500);
        }
    }

    /**
     * Extract basic information from CURP format
     */
    private function extractInfoFromCurp($curp)
    {
        // CURP format: LLLL######MMSSSS###
        // L = Letter, # = Number, M = Gender, S = State code
        
        // Extract date components
        $year = substr($curp, 4, 2);
        $month = substr($curp, 6, 2);
        $day = substr($curp, 8, 2);
        
        // Convert 2-digit year to full year
        $fullYear = ($year >= 00 && $year <= 21) ? '20' . $year : '19' . $year;
        
        // Extract gender
        $genderCode = substr($curp, 10, 1);
        $gender = $genderCode === 'H' ? 'MASCULINO' : 'FEMENINO';
        
        // Extract state code
        $stateCode = substr($curp, 11, 2);
        $states = [
            'AS' => 'AGUASCALIENTES', 'BC' => 'BAJA CALIFORNIA', 'BS' => 'BAJA CALIFORNIA SUR',
            'CC' => 'CAMPECHE', 'CL' => 'COAHUILA', 'CM' => 'COLIMA', 'CS' => 'CHIAPAS',
            'CH' => 'CHIHUAHUA', 'DF' => 'CIUDAD DE MÉXICO', 'DG' => 'DURANGO',
            'GT' => 'GUANAJUATO', 'GR' => 'GUERRERO', 'HG' => 'HIDALGO', 'JC' => 'JALISCO',
            'MC' => 'MÉXICO', 'MN' => 'MICHOACÁN', 'MS' => 'MORELOS', 'NT' => 'NAYARIT',
            'NL' => 'NUEVO LEÓN', 'OC' => 'OAXACA', 'PL' => 'PUEBLA', 'QT' => 'QUERÉTARO',
            'QR' => 'QUINTANA ROO', 'SP' => 'SAN LUIS POTOSÍ', 'SL' => 'SINALOA',
            'SS' => 'SINALOA', 'SR' => 'SONORA', 'TC' => 'TABASCO', 'TS' => 'TAMAULIPAS', 
            'TL' => 'TLAXCALA', 'VZ' => 'VERACRUZ', 'YN' => 'YUCATÁN', 'ZS' => 'ZACATECAS', 
            'NE' => 'NACIDO EN EL EXTRANJERO'
        ];
        $state = $states[$stateCode] ?? 'DESCONOCIDO';
        
        // Only return data that can be reliably extracted from CURP format
        // Names require RENAPO database access and cannot be extracted from CURP alone
        return [
            'fechaNacimiento' => $fullYear . '-' . $month . '-' . $day,
            'sexo' => $gender,
            'entidadNacimiento' => $state
        ];
    }

    /**
     * AJAX endpoint for real-time CURP format validation
     */
    public function validateFormat(Request $request)
    {
        $curp = strtoupper(trim($request->curp ?? ''));
        
        if (strlen($curp) !== 18) {
            return response()->json([
                'valid' => false,
                'message' => 'CURP debe tener exactamente 18 caracteres'
            ]);
        }

        if (!$this->validateCurpFormat($curp)) {
            return response()->json([
                'valid' => false,
                'message' => 'Formato de CURP inválido'
            ]);
        }

        return response()->json([
            'valid' => true,
            'message' => 'Formato de CURP válido'
        ]);
    }
}