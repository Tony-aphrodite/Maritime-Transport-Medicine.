<?php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class MercadoPagoService
{
    protected string $accessToken;
    protected string $baseUrl = 'https://api.mercadopago.com';

    public function __construct()
    {
        $this->accessToken = config('services.mercadopago.access_token') ?? '';
    }

    /**
     * Check if SDK is properly configured.
     */
    protected function isConfigured(): bool
    {
        return !empty($this->accessToken);
    }

    /**
     * Create a payment preference for an appointment.
     */
    public function createPreference(Appointment $appointment): array
    {
        if (!$this->isConfigured()) {
            Log::warning('MercadoPago not configured - access token missing');
            return [
                'success' => false,
                'error' => 'Mercado Pago no esta configurado. Contacte al administrador.',
            ];
        }

        try {
            $examTypeLabel = $appointment->exam_type === 'new'
                ? 'Dictamen Medico Nuevo'
                : 'Renovacion de Dictamen Medico';

            // Get user name - use nombres field if name is not available
            $userName = $appointment->user->name ?? $appointment->user->nombres ?? '';
            $userEmail = $appointment->user->email ?? '';

            Log::info('MercadoPago Service: Creating preference', [
                'appointment_id' => $appointment->id,
                'user_name' => $userName,
                'user_email' => $userEmail,
                'total' => $appointment->total,
                'exam_type' => $appointment->exam_type,
            ]);

            $preferenceData = [
                'items' => [
                    [
                        'id' => 'appointment-' . $appointment->id,
                        'title' => $examTypeLabel,
                        'description' => 'Cita medica para ' . $appointment->appointment_date->format('d/m/Y'),
                        'quantity' => 1,
                        'currency_id' => 'MXN',
                        'unit_price' => (float) $appointment->total,
                    ]
                ],
                'payer' => [
                    'name' => $userName,
                    'email' => $userEmail,
                ],
                'back_urls' => [
                    'success' => route('appointments.payment.success'),
                    'failure' => route('appointments.payment.failure'),
                    'pending' => route('appointments.payment.pending'),
                ],
                'auto_return' => 'approved',
                'external_reference' => (string) $appointment->id,
                'notification_url' => route('mercadopago.webhook'),
                'statement_descriptor' => 'LATITUD MEDICA',
                'expires' => true,
                'expiration_date_from' => now()->toIso8601String(),
                'expiration_date_to' => now()->addMinutes(15)->toIso8601String(),
            ];

            Log::info('MercadoPago Service: Sending request to API', [
                'url' => $this->baseUrl . '/checkout/preferences',
                'preference_data' => $preferenceData,
            ]);

            $response = Http::withToken($this->accessToken)
                ->post($this->baseUrl . '/checkout/preferences', $preferenceData);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('MercadoPago Service: Preference created successfully', [
                    'preference_id' => $data['id'],
                ]);
                return [
                    'success' => true,
                    'preference_id' => $data['id'],
                    'init_point' => $data['init_point'],
                    'sandbox_init_point' => $data['sandbox_init_point'] ?? $data['init_point'],
                ];
            }

            Log::error('MercadoPago API Error', [
                'status' => $response->status(),
                'body' => $response->json(),
                'headers' => $response->headers(),
            ]);

            $errorBody = $response->json();
            $errorMessage = $errorBody['message'] ?? 'Error al crear la preferencia de pago.';

            return [
                'success' => false,
                'error' => $errorMessage,
            ];

        } catch (\Exception $e) {
            Log::error('MercadoPago Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => 'Error al procesar el pago: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get payment details by ID.
     */
    public function getPayment(int $paymentId): ?array
    {
        if (!$this->isConfigured()) {
            return null;
        }

        try {
            $response = Http::withToken($this->accessToken)
                ->get($this->baseUrl . '/v1/payments/' . $paymentId);

            if ($response->successful()) {
                $payment = $response->json();
                return [
                    'id' => $payment['id'],
                    'status' => $payment['status'],
                    'status_detail' => $payment['status_detail'] ?? null,
                    'external_reference' => $payment['external_reference'] ?? null,
                    'transaction_amount' => $payment['transaction_amount'] ?? null,
                    'currency_id' => $payment['currency_id'] ?? null,
                    'payment_method_id' => $payment['payment_method_id'] ?? null,
                    'payment_type_id' => $payment['payment_type_id'] ?? null,
                    'date_approved' => $payment['date_approved'] ?? null,
                    'payer' => [
                        'email' => $payment['payer']['email'] ?? null,
                        'id' => $payment['payer']['id'] ?? null,
                    ],
                ];
            }

            Log::error('MercadoPago Get Payment Error', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);
            return null;

        } catch (\Exception $e) {
            Log::error('MercadoPago Get Payment Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Process webhook notification.
     */
    public function processWebhook(array $data): array
    {
        $type = $data['type'] ?? null;
        $action = $data['action'] ?? null;

        if ($type === 'payment') {
            $paymentId = $data['data']['id'] ?? null;

            if ($paymentId) {
                $payment = $this->getPayment($paymentId);

                if ($payment) {
                    return [
                        'success' => true,
                        'payment' => $payment,
                        'appointment_id' => $payment['external_reference'],
                    ];
                }
            }
        }

        return [
            'success' => false,
            'error' => 'Tipo de notificacion no soportado',
        ];
    }

    /**
     * Get public key for frontend.
     */
    public function getPublicKey(): string
    {
        return config('services.mercadopago.public_key');
    }

    /**
     * Check if we're in sandbox mode.
     */
    public function isSandbox(): bool
    {
        return config('services.mercadopago.sandbox', true);
    }
}
