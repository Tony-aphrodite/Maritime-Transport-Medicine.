<?php

namespace App\Services;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;
use App\Models\Appointment;
use Illuminate\Support\Facades\Log;

class MercadoPagoService
{
    protected PreferenceClient $preferenceClient;
    protected PaymentClient $paymentClient;

    public function __construct()
    {
        // Configure MercadoPago SDK
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));

        $this->preferenceClient = new PreferenceClient();
        $this->paymentClient = new PaymentClient();
    }

    /**
     * Create a payment preference for an appointment.
     */
    public function createPreference(Appointment $appointment): array
    {
        try {
            $examTypeLabel = $appointment->exam_type === 'new'
                ? 'Dictamen Medico Nuevo'
                : 'Renovacion de Dictamen Medico';

            $preference = $this->preferenceClient->create([
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
                    'name' => $appointment->user->name ?? '',
                    'email' => $appointment->user->email ?? '',
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
            ]);

            return [
                'success' => true,
                'preference_id' => $preference->id,
                'init_point' => $preference->init_point,
                'sandbox_init_point' => $preference->sandbox_init_point,
            ];
        } catch (MPApiException $e) {
            Log::error('MercadoPago API Error: ' . $e->getMessage(), [
                'status_code' => $e->getApiResponse()->getStatusCode(),
                'content' => $e->getApiResponse()->getContent(),
            ]);

            return [
                'success' => false,
                'error' => 'Error al crear la preferencia de pago: ' . $e->getMessage(),
            ];
        } catch (\Exception $e) {
            Log::error('MercadoPago Error: ' . $e->getMessage());

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
        try {
            $payment = $this->paymentClient->get($paymentId);

            return [
                'id' => $payment->id,
                'status' => $payment->status,
                'status_detail' => $payment->status_detail,
                'external_reference' => $payment->external_reference,
                'transaction_amount' => $payment->transaction_amount,
                'currency_id' => $payment->currency_id,
                'payment_method_id' => $payment->payment_method_id,
                'payment_type_id' => $payment->payment_type_id,
                'date_approved' => $payment->date_approved,
                'payer' => [
                    'email' => $payment->payer->email ?? null,
                    'id' => $payment->payer->id ?? null,
                ],
            ];
        } catch (MPApiException $e) {
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
