@extends('layouts.dashboard')

@section('title', 'Cita Confirmada')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/appointments.css') }}">
@endpush

@section('content')
<section class="appointment-dashboard">
    <div class="appointment-container">
        <div class="success-container">
            <!-- Success Icon -->
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>

            <h2>!Cita Confirmada Exitosamente!</h2>
            <p>Su pago ha sido procesado y su cita ha quedado reservada. Recibira un correo electronico con los detalles de su cita.</p>

            <!-- Appointment Details Card -->
            <div class="appointment-details">
                <h4 style="color: #1a2a4f; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-calendar-check" style="color: #28a745;"></i>
                    Detalles de su Cita
                </h4>

                <div class="summary-item">
                    <span class="label">Numero de Confirmacion</span>
                    <span class="value" style="color: #d4af37; font-family: monospace;">{{ $appointment->payment_reference }}</span>
                </div>
                <div class="summary-item">
                    <span class="label">Fecha</span>
                    <span class="value">
                        @php
                            $dayNames = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
                            $monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        @endphp
                        {{ $dayNames[$appointment->appointment_date->dayOfWeek] }}, {{ $appointment->appointment_date->day }} de {{ $monthNames[$appointment->appointment_date->month - 1] }} de {{ $appointment->appointment_date->year }}
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
                <div class="summary-item">
                    <span class="label">Zona Horaria</span>
                    <span class="value">
                        @php
                            $tzLabels = [
                                'America/Mexico_City' => 'Ciudad de Mexico (GMT-6)',
                                'America/Tijuana' => 'Tijuana (GMT-8)',
                                'America/Cancun' => 'Cancun (GMT-5)',
                                'UTC' => 'UTC'
                            ];
                        @endphp
                        {{ $tzLabels[$appointment->timezone] ?? $appointment->timezone }}
                    </span>
                </div>
                <div class="summary-item">
                    <span class="label">Tipo de Examen</span>
                    <span class="value">{{ $appointment->exam_type_label }}</span>
                </div>
                <div class="summary-item">
                    <span class="label">Total Pagado</span>
                    <span class="value" style="color: #28a745; font-weight: 700;">${{ number_format($appointment->total, 2) }} MXN</span>
                </div>
            </div>

            <!-- What's Next -->
            <div style="background: #e8f5e9; border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; text-align: left;">
                <h4 style="color: #2e7d32; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-info-circle"></i>
                    Proximos Pasos
                </h4>
                <ul style="color: #2e7d32; margin: 0; padding-left: 1.5rem;">
                    <li style="margin-bottom: 0.5rem;">Recibira un correo de confirmacion con todos los detalles.</li>
                    <li style="margin-bottom: 0.5rem;">15 minutos antes de su cita, recibira un enlace para conectarse a la videollamada.</li>
                    <li style="margin-bottom: 0.5rem;">Asegurese de tener una buena conexion a internet y un lugar tranquilo.</li>
                    <li>Tenga a la mano su identificacion oficial y los documentos que subio.</li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('dashboard') }}" class="btn-next" style="text-decoration: none;">
                    <i class="fas fa-home"></i> Ir al Panel Principal
                </a>
                <button type="button" class="btn-back" onclick="window.print();" style="cursor: pointer;">
                    <i class="fas fa-print"></i> Imprimir Comprobante
                </button>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
@media print {
    .sidebar,
    .dash-header,
    .step-navigation,
    button {
        display: none !important;
    }
    .main-content {
        margin: 0 !important;
        padding: 1rem !important;
    }
    .appointment-container {
        box-shadow: none !important;
    }
}
</style>
@endpush
