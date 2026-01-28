@extends('layouts.dashboard')

@section('title', 'Agendar Cita - Pago')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/appointments.css') }}">
@endpush

@section('content')
<section class="booking-container">
    <!-- Back Navigation -->
    <div class="back-nav">
        <a href="{{ route('appointments.step4') }}" class="btn-back-link">
            <i class="fas fa-arrow-left"></i> Volver a confirmacion
        </a>
    </div>

    <!-- Stepper -->
    <div class="stepper">
        <div class="step completed"><span><i class="fas fa-check"></i></span><p>Fecha</p></div>
        <div class="step completed"><span><i class="fas fa-check"></i></span><p>Archivos</p></div>
        <div class="step completed"><span><i class="fas fa-check"></i></span><p>Declaracion</p></div>
        <div class="step completed"><span><i class="fas fa-check"></i></span><p>Confirmacion</p></div>
        <div class="step active"><span>5</span><p>Pago</p></div>
    </div>

    <!-- Timer -->
    <div class="timer-container">
        <div class="timer-box" id="timerBox">
            <i class="fas fa-clock"></i>
            <span>Su sesion expira en: </span>
            <span id="countdown-timer">10:00</span>
        </div>
        <p class="timer-disclaimer">Su espacio reservado se liberara si no completa el pago antes de que el tiempo termine.</p>
    </div>

    <div class="payment-layout">
        <!-- Left Section - Payment Form -->
        <div class="payment-form-section">
            <div class="card-white">
                <div class="payment-header">
                    <h3>Pago Seguro con Tarjeta</h3>
                    <div class="card-icons-header">
                        <i class="fab fa-cc-visa"></i>
                        <i class="fab fa-cc-mastercard"></i>
                        <i class="fab fa-cc-amex"></i>
                    </div>
                </div>

                <form id="paymentForm" class="card-details-form">
                    @csrf
                    <div class="form-group">
                        <label>Nombre del Titular</label>
                        <input type="text" id="card_name" name="card_name" placeholder="Como aparece en el plastico" required>
                    </div>

                    <div class="form-group">
                        <label>Numero de Tarjeta</label>
                        <div class="input-with-icon-wrapper">
                            <input type="text" id="card_number" name="card_number" inputmode="numeric"
                                   placeholder="0000 0000 0000 0000" maxlength="19" required autocomplete="cc-number">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Fecha de Expiracion</label>
                            <input type="text" id="card_expiry" name="card_expiry" inputmode="numeric"
                                   placeholder="MM / AA" maxlength="7" required autocomplete="cc-exp">
                        </div>
                        <div class="form-group">
                            <label>Codigo de Seguridad (CVC)</label>
                            <input type="password" id="card_cvc" name="card_cvc" inputmode="numeric"
                                   placeholder="123" maxlength="4" required autocomplete="cc-csc">
                        </div>
                    </div>

                    <div class="secure-badge-container">
                        <div class="secure-notice">
                            <i class="fas fa-shield-alt"></i>
                            <p>Encriptacion SSL de 256 bits. Sus datos bancarios no son almacenados en nuestros servidores.</p>
                        </div>
                    </div>

                    <button type="submit" class="btn-pay-now" id="btnPay">
                        Pagar ${{ number_format($appointment->total, 2) }} MXN
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Section - Order Summary -->
        <aside class="payment-summary">
            <div class="card-white summary-order">
                <h4>Detalle del Cargo</h4>
                <div class="order-line">
                    <span>Dictamen Medico {{ $appointment->exam_type == 'new' ? 'Nuevo' : 'Renovacion' }}</span>
                    <span>${{ number_format($appointment->subtotal, 2) }}</span>
                </div>
                <div class="order-line">
                    <span>IVA (16%)</span>
                    <span>${{ number_format($appointment->tax, 2) }}</span>
                </div>
                <hr>
                <div class="order-total">
                    <span>Total Final</span>
                    <span>${{ number_format($appointment->total, 2) }} MXN</span>
                </div>

                <div class="guarantee-box">
                    <i class="fas fa-check-circle"></i>
                    <p>Al confirmar, recibira su comprobante fiscal y enlace de acceso en su correo.</p>
                </div>

                <!-- Appointment Details -->
                <div class="appointment-mini-summary">
                    <h5><i class="fas fa-calendar-check"></i> Su Cita</h5>
                    <div class="mini-detail">
                        <span>Fecha:</span>
                        <strong>
                            @php
                                $dayNames = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
                                $monthNames = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                            @endphp
                            {{ $dayNames[$appointment->appointment_date->dayOfWeek] }}, {{ $appointment->appointment_date->day }} {{ $monthNames[$appointment->appointment_date->month - 1] }}
                        </strong>
                    </div>
                    <div class="mini-detail">
                        <span>Hora:</span>
                        <strong>
                            @php
                                $hour = (int) substr($appointment->appointment_time, 0, 2);
                                $display = sprintf('%d:00 %s', $hour > 12 ? $hour - 12 : $hour, $hour >= 12 ? 'PM' : 'AM');
                            @endphp
                            {{ $display }}
                        </strong>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</section>

<!-- Processing Modal -->
<div id="processingModal" class="modal-overlay">
    <div class="modal-content" style="text-align: center; max-width: 350px;">
        <div class="spinner-container" id="spinnerContainer">
            <div class="spinner"></div>
        </div>
        <h3 id="modalTitle" style="margin: 1.5rem 0 0.5rem;">Procesando Pago</h3>
        <p id="modalMessage" style="color: #666; margin: 0;">Por favor, espere mientras procesamos su pago...</p>
        <button type="button" id="btnCancelPayment" class="btn-cancel-payment">
            <i class="fas fa-times"></i> Cancelar y Reintentar
        </button>
    </div>
</div>

<style>
.spinner-container {
    display: flex;
    justify-content: center;
}
.spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #e0e0e0;
    border-top-color: var(--accent-gold);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}
.btn-cancel-payment {
    margin-top: 1.5rem;
    padding: 12px 25px;
    background: transparent;
    border: 2px solid #dc3545;
    color: #dc3545;
    border-radius: 50px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-cancel-payment:hover {
    background: #dc3545;
    color: white;
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
            value = value.substring(0, 2) + ' / ' + value.substring(2, 4);
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
    const countdownEl = document.getElementById('countdown-timer');
    const timerBox = document.getElementById('timerBox');

    const timer = setInterval(function() {
        timeLeft--;
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        countdownEl.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

        if (timeLeft <= 60) {
            timerBox.classList.add('timer-urgent');
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
    const btnCancelPayment = document.getElementById('btnCancelPayment');
    const modalTitle = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');
    const spinnerContainer = document.getElementById('spinnerContainer');

    let paymentAbortController = null;

    paymentForm.addEventListener('submit', function(e) {
        e.preventDefault();

        // Basic validation
        const cardNum = cardNumber.value.replace(/\s/g, '');
        if (cardNum.length < 13 || cardNum.length > 19) {
            alert('Numero de tarjeta invalido.');
            return;
        }

        // Reset modal state
        modalTitle.textContent = 'Procesando Pago';
        modalMessage.textContent = 'Por favor, espere mientras procesamos su pago...';
        spinnerContainer.style.display = 'flex';

        // Show processing modal
        processingModal.style.display = 'flex';
        btnPay.disabled = true;

        // Create abort controller for cancellation
        paymentAbortController = new AbortController();

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
            }),
            signal: paymentAbortController.signal
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                clearInterval(timer);
                window.location.href = data.redirect;
            } else {
                closePaymentModal();
                alert(data.message || 'Error al procesar el pago.');
            }
        })
        .catch(error => {
            if (error.name === 'AbortError') {
                // Payment was cancelled by user
                return;
            }
            closePaymentModal();
            alert('Error al procesar el pago. Por favor, intente de nuevo.');
            console.error('Payment error:', error);
        });
    });

    // Cancel payment handler
    btnCancelPayment.addEventListener('click', function() {
        if (paymentAbortController) {
            paymentAbortController.abort();
        }
        closePaymentModal();
    });

    function closePaymentModal() {
        processingModal.style.display = 'none';
        btnPay.disabled = false;
    }
});
</script>
@endpush
