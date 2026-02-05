@extends('layouts.dashboard')

@section('title', 'Agendar Cita - Pago')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/appointments.css') }}">
<style>
.mercadopago-button {
    background: linear-gradient(135deg, #009EE3 0%, #00B1EA 100%);
    color: white;
    border: none;
    padding: 16px 32px;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 8px;
    cursor: pointer;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 158, 227, 0.3);
}
.mercadopago-button:hover {
    background: linear-gradient(135deg, #007AB8 0%, #009EE3 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 158, 227, 0.4);
}
.mercadopago-button:disabled {
    background: #ccc;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}
.mercadopago-button img {
    height: 24px;
}
.payment-methods-info {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    margin-top: 20px;
}
.payment-methods-info h5 {
    color: #333;
    margin-bottom: 15px;
    font-size: 0.95rem;
}
.payment-methods-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}
.payment-method-item {
    background: white;
    border-radius: 8px;
    padding: 10px;
    text-align: center;
    border: 1px solid #e0e0e0;
}
.payment-method-item img {
    height: 30px;
    object-fit: contain;
}
.payment-method-item span {
    display: block;
    font-size: 0.75rem;
    color: #666;
    margin-top: 5px;
}
.secure-payment-badge {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 20px;
    color: #28a745;
    font-size: 0.9rem;
}
.secure-payment-badge i {
    font-size: 1.2rem;
}
.mp-logo {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
}
.mp-logo img {
    height: 40px;
}
.payment-loading {
    display: none;
    text-align: center;
    padding: 40px;
}
.payment-loading.active {
    display: block;
}
.payment-form-content.hidden {
    display: none;
}
</style>
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
            <span id="countdown-timer">15:00</span>
        </div>
        <p class="timer-disclaimer">Su espacio reservado se liberara si no completa el pago antes de que el tiempo termine.</p>
    </div>

    <div class="payment-layout">
        <!-- Left Section - Payment Form -->
        <div class="payment-form-section">
            <div class="card-white">
                <!-- Loading State -->
                <div class="payment-loading" id="paymentLoading">
                    <div class="spinner-container">
                        <div class="spinner"></div>
                    </div>
                    <h3 style="margin-top: 1.5rem;">Preparando pago...</h3>
                    <p style="color: #666;">Por favor espere mientras lo redirigimos a Mercado Pago.</p>
                </div>

                <!-- Payment Content -->
                <div class="payment-form-content" id="paymentContent">
                    <div class="mp-logo">
                        <img src="https://http2.mlstatic.com/frontend-assets/mp-web-navigation/ui-navigation/6.6.92/mercadopago/logo__large.png" alt="Mercado Pago">
                    </div>

                    <div class="payment-header" style="text-align: center; margin-bottom: 25px;">
                        <h3 style="margin-bottom: 10px;">Pago Seguro</h3>
                        <p style="color: #666; font-size: 0.95rem;">Complete su pago de forma segura con Mercado Pago</p>
                    </div>

                    <button type="button" class="mercadopago-button" id="btnPayMercadoPago">
                        <img src="https://http2.mlstatic.com/frontend-assets/mp-web-navigation/ui-navigation/6.6.92/mercadopago/logo__small.png" alt="MP">
                        Pagar ${{ number_format($appointment->total, 2) }} MXN
                    </button>

                    <div class="secure-payment-badge">
                        <i class="fas fa-shield-alt"></i>
                        <span>Pago protegido por Mercado Pago</span>
                    </div>

                    <div class="payment-methods-info">
                        <h5><i class="fas fa-credit-card"></i> Metodos de pago aceptados:</h5>
                        <div class="payment-methods-grid">
                            <div class="payment-method-item">
                                <i class="fab fa-cc-visa" style="font-size: 30px; color: #1A1F71;"></i>
                                <span>Visa</span>
                            </div>
                            <div class="payment-method-item">
                                <i class="fab fa-cc-mastercard" style="font-size: 30px; color: #EB001B;"></i>
                                <span>Mastercard</span>
                            </div>
                            <div class="payment-method-item">
                                <i class="fab fa-cc-amex" style="font-size: 30px; color: #006FCF;"></i>
                                <span>Amex</span>
                            </div>
                            <div class="payment-method-item">
                                <i class="fas fa-store" style="font-size: 30px; color: #FF6600;"></i>
                                <span>OXXO</span>
                            </div>
                        </div>
                        <p style="margin-top: 15px; font-size: 0.85rem; color: #666; text-align: center;">
                            Tarjetas de credito, debito, efectivo en OXXO y mas opciones disponibles
                        </p>
                    </div>
                </div>
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

<style>
.spinner-container {
    display: flex;
    justify-content: center;
}
.spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #e0e0e0;
    border-top-color: #009EE3;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Countdown timer
    let timeLeft = 15 * 60; // 15 minutes in seconds
    const countdownEl = document.getElementById('countdown-timer');
    const timerBox = document.getElementById('timerBox');

    const timer = setInterval(function() {
        timeLeft--;
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        countdownEl.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

        if (timeLeft <= 180) { // 3 minutes warning
            timerBox.classList.add('timer-warning');
        }

        if (timeLeft <= 60) {
            timerBox.classList.remove('timer-warning');
            timerBox.classList.add('timer-urgent');
        }

        if (timeLeft <= 0) {
            clearInterval(timer);
            alert('El tiempo para completar el pago ha expirado. Sera redirigido al inicio.');
            window.location.href = '{{ route("appointments.step1") }}';
        }
    }, 1000);

    // Mercado Pago payment button
    const btnPayMercadoPago = document.getElementById('btnPayMercadoPago');
    const paymentLoading = document.getElementById('paymentLoading');
    const paymentContent = document.getElementById('paymentContent');

    btnPayMercadoPago.addEventListener('click', function() {
        // Show loading state
        paymentContent.classList.add('hidden');
        paymentLoading.classList.add('active');
        btnPayMercadoPago.disabled = true;

        // Create preference and redirect to Mercado Pago
        fetch('{{ route("mercadopago.create-preference") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.init_point) {
                // Redirect to Mercado Pago checkout
                window.location.href = data.init_point;
            } else {
                // Show error
                paymentContent.classList.remove('hidden');
                paymentLoading.classList.remove('active');
                btnPayMercadoPago.disabled = false;
                alert(data.message || 'Error al iniciar el pago. Por favor, intente de nuevo.');
            }
        })
        .catch(error => {
            console.error('Payment error:', error);
            paymentContent.classList.remove('hidden');
            paymentLoading.classList.remove('active');
            btnPayMercadoPago.disabled = false;
            alert('Error al conectar con el servidor. Por favor, intente de nuevo.');
        });
    });
});
</script>
@endpush
