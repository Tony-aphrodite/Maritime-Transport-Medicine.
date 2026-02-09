<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MercadoPagoController extends Controller
{
    protected MercadoPagoService $mercadoPago;

    public function __construct(MercadoPagoService $mercadoPago)
    {
        $this->mercadoPago = $mercadoPago;
    }

    /**
     * Create a payment preference and return the checkout URL.
     */
    public function createPreference(Request $request)
    {
        $appointmentId = session('appointment.id');

        if (!$appointmentId) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontro la cita.',
            ], 400);
        }

        $appointment = Appointment::find($appointmentId);

        if (!$appointment) {
            return response()->json([
                'success' => false,
                'message' => 'Cita no encontrada.',
            ], 404);
        }

        $result = $this->mercadoPago->createPreference($appointment);

        if ($result['success']) {
            // Store preference ID in session for later verification
            session(['mercadopago_preference_id' => $result['preference_id']]);

            return response()->json([
                'success' => true,
                'preference_id' => $result['preference_id'],
                'init_point' => $this->mercadoPago->isSandbox()
                    ? $result['sandbox_init_point']
                    : $result['init_point'],
                'public_key' => $this->mercadoPago->getPublicKey(),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['error'],
        ], 500);
    }

    /**
     * Handle successful payment return.
     */
    public function success(Request $request)
    {
        $paymentId = $request->get('payment_id');
        $status = $request->get('status');
        $externalReference = $request->get('external_reference');

        Log::info('MercadoPago Success Callback', [
            'payment_id' => $paymentId,
            'status' => $status,
            'external_reference' => $externalReference,
        ]);

        $appointment = Appointment::find($externalReference);

        if (!$appointment) {
            return redirect()->route('dashboard')
                ->with('error', 'Cita no encontrada.');
        }

        // Get payment details from MercadoPago
        if ($paymentId) {
            $payment = $this->mercadoPago->getPayment($paymentId);

            if ($payment && $payment['status'] === 'approved') {
                // Update appointment with payment info
                $appointment->update([
                    'status' => Appointment::STATUS_CONFIRMED,
                    'payment_status' => 'paid',
                    'payment_date' => now(),
                    'payment_reference' => $paymentId,
                    'payment_method' => 'mercadopago',
                ]);

                // Clear session data
                session()->forget([
                    'appointment',
                    'mercadopago_preference_id',
                ]);

                return redirect()->route('appointments.confirmation', $appointment->id)
                    ->with('success', 'Pago completado exitosamente.');
            }
        }

        // If we can't verify payment, mark as pending
        $appointment->update([
            'status' => Appointment::STATUS_PENDING_PAYMENT,
            'payment_reference' => $paymentId,
            'payment_method' => 'mercadopago',
        ]);

        return redirect()->route('appointments.confirmation', $appointment->id)
            ->with('warning', 'Su pago esta siendo procesado. Recibira una confirmacion por correo.');
    }

    /**
     * Handle failed payment return.
     */
    public function failure(Request $request)
    {
        Log::warning('MercadoPago Failure Callback', $request->all());

        $externalReference = $request->get('external_reference');

        return redirect()->route('appointments.step5')
            ->with('error', 'El pago no pudo ser procesado. Por favor, intente de nuevo.');
    }

    /**
     * Handle pending payment return.
     */
    public function pending(Request $request)
    {
        Log::info('MercadoPago Pending Callback', $request->all());

        $externalReference = $request->get('external_reference');
        $appointment = Appointment::find($externalReference);

        if ($appointment) {
            $appointment->update([
                'status' => Appointment::STATUS_PENDING_PAYMENT,
                'payment_reference' => $request->get('payment_id'),
                'payment_method' => 'mercadopago',
            ]);

            // Clear session data
            session()->forget([
                'appointment',
                'mercadopago_preference_id',
            ]);

            return redirect()->route('appointments.confirmation', $appointment->id)
                ->with('info', 'Su pago esta pendiente de confirmacion. Recibira una notificacion cuando sea aprobado.');
        }

        return redirect()->route('dashboard')
            ->with('warning', 'Pago pendiente de confirmacion.');
    }

    /**
     * Handle MercadoPago webhook notifications.
     */
    public function webhook(Request $request)
    {
        Log::info('MercadoPago Webhook Received', $request->all());

        $type = $request->get('type');
        $data = $request->all();

        if ($type === 'payment') {
            $result = $this->mercadoPago->processWebhook($data);

            if ($result['success'] && isset($result['payment'])) {
                $payment = $result['payment'];
                $appointmentId = $result['appointment_id'];

                $appointment = Appointment::find($appointmentId);

                if ($appointment) {
                    switch ($payment['status']) {
                        case 'approved':
                            $appointment->update([
                                'status' => Appointment::STATUS_CONFIRMED,
                                'payment_status' => 'paid',
                                'payment_date' => $payment['date_approved'] ?? now(),
                                'payment_reference' => $payment['id'],
                                'payment_method' => 'mercadopago',
                            ]);
                            Log::info('Payment approved for appointment: ' . $appointmentId);
                            break;

                        case 'pending':
                        case 'in_process':
                            $appointment->update([
                                'status' => Appointment::STATUS_PENDING_PAYMENT,
                                'payment_reference' => $payment['id'],
                                'payment_method' => 'mercadopago',
                            ]);
                            Log::info('Payment pending for appointment: ' . $appointmentId);
                            break;

                        case 'rejected':
                        case 'cancelled':
                            $appointment->update([
                                'status' => Appointment::STATUS_CANCELLED,
                                'payment_status' => 'failed',
                                'payment_reference' => $payment['id'],
                                'payment_method' => 'mercadopago',
                            ]);
                            Log::warning('Payment rejected/cancelled for appointment: ' . $appointmentId);
                            break;
                    }
                }
            }
        }

        // Always return 200 to acknowledge receipt
        return response()->json(['status' => 'ok'], 200);
    }
}
