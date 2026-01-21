@extends('layouts.dashboard')

@section('title', 'Agendar Cita - Pago')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/appointments.css') }}">
@endpush

@section('content')
<section class="appointment-dashboard">
    <!-- Stepper -->
    <div class="stepper">
        <div class="step completed">
            <div class="step-number"></div>
            <span class="step-label">Fecha y Hora</span>
        </div>
        <div class="step completed">
            <div class="step-number"></div>
            <span class="step-label">Archivos</span>
        </div>
        <div class="step completed">
            <div class="step-number"></div>
            <span class="step-label">Declaracion</span>
        </div>
        <div class="step completed">
            <div class="step-number"></div>
            <span class="step-label">Confirmacion</span>
        </div>
        <div class="step active">
            <div class="step-number">5</div>
            <span class="step-label">Pago</span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="appointment-container">
        <div class="payment-form">
            <!-- Payment Card Form -->
            <div class="payment-card">
                <h3><i class="fas fa-lock"></i> Pago Seguro con Tarjeta</h3>

                <!-- Card Icons -->
                <div class="card-icons">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/200px-Visa_Inc._logo.svg.png" alt="Visa" style="height: 25px;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/200px-Mastercard-logo.svg.png" alt="Mastercard" style="height: 25px;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/American_Express_logo_%282018%29.svg/200px-American_Express_logo_%282018%29.svg.png" alt="Amex" style="height: 25px;">
                </div>

                <form id="paymentForm">
                    @csrf
                    <div class="form-group">
                        <label for="card_name">Nombre del Titular <span class="required">*</span></label>
                        <input type="text" id="card_name" name="card_name" placeholder="Como aparece en la tarjeta" required>
                    </div>

                    <div class="form-group">
                        <label for="card_number">Numero de Tarjeta <span class="required">*</span></label>
                        <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456"
                               maxlength="19" required autocomplete="cc-number">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="card_expiry">Fecha de Expiracion <span class="required">*</span></label>
                            <input type="text" id="card_expiry" name="card_expiry" placeholder="MM/AA"
                                   maxlength="5" required autocomplete="cc-exp">
                        </div>
                        <div class="form-group">
                            <label for="card_cvc">Codigo de Seguridad (CVC) <span class="required">*</span></label>
                            <input type="text" id="card_cvc" name="card_cvc" placeholder="123"
                                   maxlength="4" required autocomplete="cc-csc">
                        </div>
                    </div>

                    <div class="secure-badge">
                        <i class="fas fa-shield-alt"></i>
                        <span>Encriptacion SSL de 256 bits. Sus datos bancarios no son almacenados.</span>
                    </div>

                    <button type="submit" class="btn-pay" id="btnPay" style="margin-top: 1.5rem;">
                        <i class="fas fa-lock"></i> Pagar ${{ number_format($appointment->total, 2) }} MXN
                    </button>
                </form>
            </div>

            <!-- Order Summary -->
            <div>
                <div class="price-summary">
                    <h4><i class="fas fa-receipt"></i> Detalle del Cargo</h4>
                    <div class="price-row">
                        <span>Dictamen Medico {{ $appointment->exam_type == 'new' ? 'Nuevo' : 'Renovacion' }}</span>
                        <span>${{ number_format($appointment->subtotal, 2) }}</span>
                    </div>
                    <div class="price-row">
                        <span>IVA (16%)</span>
                        <span>${{ number_format($appointment->tax, 2) }}</span>
                    </div>
                    <div class="price-row total">
                        <span>Total</span>
                        <span class="amount">${{ number_format($appointment->total, 2) }} MXN</span>
                    </div>
                </div>

                <!-- Appointment Summary -->
                <div class="summary-card" style="margin-top: 1rem;">
                    <h4><i class="fas fa-calendar-check"></i> Su Cita</h4>
                    <div class="summary-item">
                        <span class="label">Fecha</span>
                        <span class="value">
                            @php
                                $dayNames = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
                                $monthNames = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                            @endphp
                            {{ $dayNames[$appointment->appointment_date->dayOfWeek] }}, {{ $appointment->appointment_date->day }} {{ $monthNames[$appointment->appointment_date->month - 1] }}
                        </span>
                    </div>
                    <div class="summary-item">
                        <span class="label">Hora</span>
                        <span class="value">
                            @php
                                $hour = (int) substr($appointment->appointment_time, 0, 2);
                                $display = sprintf('%d:00 %s', $hour > 12 ? $hour - 12 : $hour, $hour >= 12 ? 'PM' : 'AM');
                            @endphp
                            {{ $display }}
                        </span>
                    </div>
                </div>

                <!-- Timer Warning -->
                <div style="background: #fff3cd; border-radius: 8px; padding: 1rem; margin-top: 1rem; text-align: center;">
                    <i class="fas fa-clock" style="color: #856404; font-size: 1.5rem;"></i>
                    <p style="color: #856404; margin: 0.5rem 0 0 0; font-size: 0.9rem;">
                        <strong>Tiempo restante para completar el pago:</strong><br>
                        <span id="countdown" style="font-size: 1.5rem; font-weight: 700;">10:00</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="step-navigation" style="justify-content: flex-start;">
            <a href="{{ route('appointments.step4') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver a Revisar
            </a>
        </div>
    </div>
</section>

<!-- Processing Modal -->
<div id="processingModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 3rem; border-radius: 16px; text-align: center; max-width: 400px;">
        <div style="width: 60px; height: 60px; border: 4px solid #e0e0e0; border-top-color: #d4af37; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 1.5rem;"></div>
        <h3 style="color: #1a2a4f; margin-bottom: 0.5rem;">Procesando Pago</h3>
        <p style="color: #666;">Por favor, no cierre esta ventana...</p>
    </div>
</div>

<style>
@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format card number
    const cardNumber = document.getElementById('card_number');
    cardNumber.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s/g, '').replace(/\D/g, '');
        let formatted = value.match(/.{1,4}/g)?.join(' ') || value;
        e.target.value = formatted;
    });

    // Format expiry date
    const cardExpiry = document.getElementById('card_expiry');
    cardExpiry.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        e.target.value = value;
    });

    // CVC - numbers only
    const cardCvc = document.getElementById('card_cvc');
    cardCvc.addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '');
    });

    // Countdown timer
    let timeLeft = 10 * 60; // 10 minutes in seconds
    const countdownEl = document.getElementById('countdown');

    const timer = setInterval(function() {
        timeLeft--;
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        countdownEl.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

        if (timeLeft <= 60) {
            countdownEl.style.color = '#dc3545';
        }

        if (timeLeft <= 0) {
            clearInterval(timer);
            alert('El tiempo para completar el pago ha expirado. Sera redirigido al inicio.');
            window.location.href = '{{ route("appointments.step1") }}';
        }
    }, 1000);

    // Payment form submission
    const paymentForm = document.getElementById('paymentForm');
    const processingModal = document.getElementById('processingModal');
    const btnPay = document.getElementById('btnPay');

    paymentForm.addEventListener('submit', function(e) {
        e.preventDefault();

        // Basic validation
        const cardNum = cardNumber.value.replace(/\s/g, '');
        if (cardNum.length < 13 || cardNum.length > 19) {
            alert('Numero de tarjeta invalido.');
            return;
        }

        // Show processing modal
        processingModal.style.display = 'flex';
        btnPay.disabled = true;

        // Submit payment
        fetch('{{ route("appointments.payment.process") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                card_name: document.getElementById('card_name').value,
                // In production, use a payment gateway like Stripe - never send card details to your server
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                clearInterval(timer);
                window.location.href = data.redirect;
            } else {
                processingModal.style.display = 'none';
                btnPay.disabled = false;
                alert(data.message || 'Error al procesar el pago.');
            }
        })
        .catch(error => {
            processingModal.style.display = 'none';
            btnPay.disabled = false;
            alert('Error al procesar el pago. Por favor, intente de nuevo.');
            console.error('Payment error:', error);
        });
    });
});
</script>
@endpush
