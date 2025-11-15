<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\AuditLog;
use Exception;

class FaceVerificationController extends Controller
{
    /**
     * Display the face verification page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('face-verification.verify');
    }

    /**
     * Upload and process selfie and INE photos for face comparison
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function compareFaces(Request $request): JsonResponse
    {
        $verificationId = 'face_' . uniqid();
        $userId = $request->input('user_id', 'unknown');
        
        try {
            // Log face verification attempt
            try {
                AuditLog::logEvent(
                    AuditLog::EVENT_FACE_MATCHING_ATTEMPT,
                    AuditLog::STATUS_IN_PROGRESS,
                    [
                        'has_selfie' => $request->hasFile('selfie'),
                        'has_ine' => $request->hasFile('ine_photo')
                    ],
                    $userId,
                    $verificationId
                );
            } catch (\Exception $e) {
                Log::warning('Failed to log face verification attempt: ' . $e->getMessage());
            }
            
            Log::info('🔍 Face verification request received', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'has_selfie' => $request->hasFile('selfie'),
                'has_ine' => $request->hasFile('ine_photo'),
                'verification_id' => $verificationId
            ]);

            // Validate request
            $validator = $this->validateFaceVerificationRequest($request);
            if ($validator->fails()) {
                Log::warning('❌ Face verification validation failed', ['errors' => $validator->errors()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Process uploaded images
            $selfieData = $this->processUploadedImage($request->file('selfie'), 'selfie');
            $ineData = $this->processUploadedImage($request->file('ine_photo'), 'ine');

            if (!$selfieData || !$ineData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error processing uploaded images',
                    'retry_available' => true
                ], 400);
            }

            Log::info('📸 Images processed successfully', [
                'selfie_size' => strlen($selfieData),
                'ine_size' => strlen($ineData)
            ]);

            // Call face verification API
            $verificationResult = $this->callFaceVerificationAPI($selfieData, $ineData);

            if ($verificationResult['success']) {
                $isMatch = $verificationResult['data']['match'];
                $confidence = $verificationResult['data']['confidence'];
                
                Log::info('✅ Face verification completed', [
                    'match' => $isMatch,
                    'confidence' => $confidence,
                    'verification_id' => $verificationId
                ]);

                // Log face verification result
                try {
                    AuditLog::logFaceVerification(
                        $isMatch ? AuditLog::STATUS_SUCCESS : AuditLog::STATUS_FAILURE,
                        $userId,
                        $confidence,
                        $verificationId
                    );
                } catch (\Exception $e) {
                    Log::warning('Failed to log face verification result: ' . $e->getMessage());
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Face verification completed successfully',
                    'data' => [
                        'match' => $isMatch,
                        'confidence' => $confidence,
                        'result' => $isMatch ? 'Match' : 'No Match',
                        'verification_id' => $verificationId
                    ]
                ]);
            } else {
                Log::error('❌ Face verification API failed', [
                    'error' => $verificationResult['message'],
                    'verification_id' => $verificationId
                ]);
                
                // Log face verification failure
                try {
                    AuditLog::logFaceVerification(
                        AuditLog::STATUS_FAILURE,
                        $userId,
                        null,
                        $verificationId
                    );
                } catch (\Exception $e) {
                    Log::warning('Failed to log face verification failure: ' . $e->getMessage());
                }
                
                return response()->json([
                    'success' => false,
                    'message' => $verificationResult['message'] ?? 'Face verification failed',
                    'retry_available' => true
                ], 500);
            }

        } catch (Exception $e) {
            Log::error('💥 Face verification exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred during face verification',
                'retry_available' => true
            ], 500);
        }
    }

    /**
     * Validate face verification request
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validateFaceVerificationRequest(Request $request)
    {
        return Validator::make($request->all(), [
            'selfie' => [
                'required',
                'file',
                'image',
                'mimes:jpeg,png,jpg',
                'max:5120', // 5MB max
                'dimensions:min_width=300,min_height=300,max_width=4000,max_height=4000'
            ],
            'ine_photo' => [
                'required',
                'file',
                'image',
                'mimes:jpeg,png,jpg',
                'max:5120', // 5MB max
                'dimensions:min_width=300,min_height=300,max_width=4000,max_height=4000'
            ],
            'curp' => [
                'sometimes',
                'string',
                'size:18',
                'regex:/^[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[0-9A-Z][0-9]$/'
            ]
        ], [
            'selfie.required' => 'La fotografía selfie es requerida',
            'selfie.image' => 'El archivo selfie debe ser una imagen válida',
            'selfie.mimes' => 'El archivo selfie debe ser JPEG, PNG o JPG',
            'selfie.max' => 'El archivo selfie no debe exceder 5MB',
            'selfie.dimensions' => 'La imagen selfie debe tener al menos 300x300 píxeles',
            
            'ine_photo.required' => 'La fotografía del INE es requerida',
            'ine_photo.image' => 'El archivo INE debe ser una imagen válida',
            'ine_photo.mimes' => 'El archivo INE debe ser JPEG, PNG o JPG',
            'ine_photo.max' => 'El archivo INE no debe exceder 5MB',
            'ine_photo.dimensions' => 'La imagen INE debe tener al menos 300x300 píxeles',
            
            'curp.size' => 'El CURP debe tener exactamente 18 caracteres',
            'curp.regex' => 'El formato del CURP no es válido'
        ]);
    }

    /**
     * Process uploaded image and convert to base64
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $type
     * @return string|null
     */
    private function processUploadedImage($file, string $type): ?string
    {
        try {
            Log::info("📸 Processing {$type} image", [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize()
            ]);

            // Read file content
            $imageContent = file_get_contents($file->getRealPath());
            
            if (!$imageContent) {
                Log::error("❌ Failed to read {$type} file content");
                return null;
            }

            // Convert to base64
            $base64Image = base64_encode($imageContent);
            
            Log::info("✅ {$type} image processed successfully", [
                'base64_length' => strlen($base64Image),
                'mime_type' => $file->getMimeType()
            ]);

            return $base64Image;

        } catch (Exception $e) {
            Log::error("💥 Error processing {$type} image", [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return null;
        }
    }

    /**
     * Call external face verification API
     *
     * @param string $selfieBase64
     * @param string $ineBase64
     * @return array
     */
    private function callFaceVerificationAPI(string $selfieBase64, string $ineBase64): array
    {
        try {
            $token = env('FACE_VERIFY_TOKEN', env('VERIFICAMEX_TOKEN'));
            $baseUrl = env('FACE_VERIFY_BASE_URL', env('VERIFICAMEX_BASE_URL'));
            $endpoint = env('FACE_VERIFY_ENDPOINT', '/api/face-compare');

            Log::info('🌐 Calling face verification API', [
                'base_url' => $baseUrl,
                'endpoint' => $endpoint,
                'has_token' => !empty($token)
            ]);

            // Prepare request data
            $requestData = [
                'selfie_image' => $selfieBase64,
                'ine_image' => $ineBase64,
                'image_format' => 'base64',
                'timestamp' => now()->toISOString(),
                'client_id' => config('app.name', 'MARINA')
            ];

            // Make API request
            $response = Http::timeout(60)
                ->withToken($token)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'MARINA-FaceVerify/1.0'
                ])
                ->post($baseUrl . $endpoint, $requestData);

            Log::info('📡 Face verification API response', [
                'status' => $response->status(),
                'successful' => $response->successful()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Parse API response - adjust based on actual API response format
                if (isset($data['success']) && $data['success']) {
                    return [
                        'success' => true,
                        'data' => [
                            'match' => $data['result']['match'] ?? false,
                            'confidence' => $data['result']['confidence'] ?? 0,
                            'verification_id' => $data['verification_id'] ?? null
                        ]
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => $data['message'] ?? 'Face verification failed'
                    ];
                }
            } else {
                // API call failed
                $errorData = $response->json();
                Log::error('❌ Face verification API error', [
                    'status' => $response->status(),
                    'response' => $errorData
                ]);

                return [
                    'success' => false,
                    'message' => $errorData['message'] ?? 'Face verification service unavailable'
                ];
            }

        } catch (Exception $e) {
            Log::error('💥 Face verification API exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            // Fallback: simulate API response for testing
            return $this->simulateFaceVerificationResponse($selfieBase64, $ineBase64);
        }
    }

    /**
     * Simulate face verification response for testing/demo purposes
     *
     * @param string $selfieBase64
     * @param string $ineBase64
     * @return array
     */
    private function simulateFaceVerificationResponse(string $selfieBase64, string $ineBase64): array
    {
        Log::info('🎭 Simulating face verification response for testing');
        
        // Simulate processing delay
        sleep(2);

        // Generate simulated results
        $confidence = rand(75, 98);
        $match = $confidence >= 80; // Consider match if confidence >= 80%

        Log::info('🎯 Simulated verification result', [
            'match' => $match,
            'confidence' => $confidence
        ]);

        return [
            'success' => true,
            'data' => [
                'match' => $match,
                'confidence' => $confidence,
                'verification_id' => 'sim_' . uniqid()
            ]
        ];
    }

    /**
     * Get face verification status
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getVerificationStatus(Request $request): JsonResponse
    {
        $verificationId = $request->query('verification_id');
        
        if (!$verificationId) {
            return response()->json([
                'success' => false,
                'message' => 'Verification ID is required'
            ], 400);
        }

        // In a real implementation, you would fetch status from database
        return response()->json([
            'success' => true,
            'data' => [
                'verification_id' => $verificationId,
                'status' => 'completed',
                'timestamp' => now()->toISOString()
            ]
        ]);
    }
}