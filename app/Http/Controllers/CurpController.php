<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

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

            // Validate CURP format
            if (!$this->validateCurpFormat($curp)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Formato de CURP inválido. Verificar estructura del código.',
                    'data' => null
                ], 422);
            }

            // Call VerificaMex CURP API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->verificamexToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->timeout(30)->post($this->verificamexBaseUrl . '/curp', [
                'curp' => $curp
            ]);

            if ($response->successful()) {
                $apiData = $response->json();
                
                // VerificaMex API response structure
                if ($apiData && isset($apiData['success']) && $apiData['success']) {
                    $curpData = $apiData['data'] ?? [];
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'CURP válido y verificado exitosamente contra RENAPO',
                        'data' => [
                            'curp' => $curp,
                            'valid' => true,
                            'details' => [
                                'nombres' => $curpData['nombres'] ?? null,
                                'primerApellido' => $curpData['primer_apellido'] ?? null,
                                'segundoApellido' => $curpData['segundo_apellido'] ?? null,
                                'fechaNacimiento' => $curpData['fecha_nacimiento'] ?? null,
                                'sexo' => $curpData['sexo'] ?? null,
                                'entidadNacimiento' => $curpData['entidad_nacimiento'] ?? null,
                                'nacionalidad' => $curpData['nacionalidad'] ?? 'MEXICANA',
                                'estatus' => $curpData['estatus'] ?? null
                            ],
                            'verification_date' => now()->toISOString(),
                            'certificate_url' => $apiData['certificate_url'] ?? null
                        ]
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => $apiData['message'] ?? 'CURP no encontrado en la base de datos de RENAPO',
                        'data' => [
                            'curp' => $curp,
                            'valid' => false,
                            'verification_date' => now()->toISOString()
                        ]
                    ], 404);
                }
            } else {
                // API call failed
                Log::error('VerificaMex API Error', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Error temporal del servicio de validación. Intente nuevamente.',
                    'data' => null
                ], 503);
            }

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