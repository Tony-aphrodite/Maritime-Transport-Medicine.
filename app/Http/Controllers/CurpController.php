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
        // Set your VerificaMex API credentials here
        // In production, store these in .env file
        $this->verificamexToken = env('VERIFICAMEX_TOKEN', 'your-bearer-token-here');
        $this->verificamexBaseUrl = env('VERIFICAMEX_BASE_URL', 'https://api.verificamex.com/v1');
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

            // Call VerificaMex API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->verificamexToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->timeout(30)->post($this->verificamexBaseUrl . '/curp/validate', [
                'curp' => $curp
            ]);

            if ($response->successful()) {
                $apiData = $response->json();
                
                if ($apiData && isset($apiData['valid']) && $apiData['valid']) {
                    return response()->json([
                        'success' => true,
                        'message' => 'CURP válido y verificado exitosamente',
                        'data' => [
                            'curp' => $curp,
                            'valid' => true,
                            'details' => $apiData['data'] ?? null,
                            'verification_date' => now()->toISOString()
                        ]
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'CURP no encontrado en la base de datos de RENAPO',
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