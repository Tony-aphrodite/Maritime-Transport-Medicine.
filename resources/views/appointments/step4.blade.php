@extends('layouts.dashboard')

@section('title', 'Agendar Cita - Confirmacion')

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
        <div class="step active">
            <div class="step-number">4</div>
            <span class="step-label">Confirmacion</span>
        </div>
        <div class="step">
            <div class="step-number">5</div>
            <span class="step-label">Pago</span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="appointment-container">
        <div class="appointment-header">
            <h2><i class="fas fa-clipboard-check"></i> Verifique su Informacion</h2>
            <p>Revise cuidadosamente los datos de su cita antes de proceder al pago.</p>
        </div>

        <div class="confirmation-summary">
            <!-- Personal Information -->
            <div class="summary-card">
                <h4><i class="fas fa-user"></i> Informacion Personal</h4>
                <div class="summary-item">
                    <span class="label">Nombre Completo</span>
                    <span class="value">{{ $user->full_name ?? $user->name }}</span>
                </div>
                <div class="summary-item">
                    <span class="label">Correo Electronico</span>
                    <span class="value">{{ $user->email }}</span>
                </div>
                @if($user->telefono_movil)
                <div class="summary-item">
                    <span class="label">Telefono</span>
                    <span class="value">{{ $user->telefono_movil }}</span>
                </div>
                @endif
                @if($user->curp)
                <div class="summary-item">
                    <span class="label">CURP</span>
                    <span class="value">{{ $user->curp }}</span>
                </div>
                @endif
            </div>

            <!-- Appointment Details -->
            <div class="summary-card">
                <h4><i class="fas fa-calendar-alt"></i> Detalles de la Cita</h4>
                <div class="summary-item">
                    <span class="label">Fecha</span>
                    <span class="value">
                        @php
                            $date = \Carbon\Carbon::parse($appointmentData['date']);
                            $dayNames = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
                            $monthNames = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                        @endphp
                        {{ $dayNames[$date->dayOfWeek] }}, {{ $date->day }} de {{ $monthNames[$date->month - 1] }}
                    </span>
                </div>
                <div class="summary-item">
                    <span class="label">Hora</span>
                    <span class="value">
                        @php
                            $hour = (int) substr($appointmentData['time'], 0, 2);
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
                                'America/Mexico_City' => 'CDMX (GMT-6)',
                                'America/Tijuana' => 'Tijuana (GMT-8)',
                                'America/Cancun' => 'Cancun (GMT-5)',
                                'UTC' => 'UTC'
                            ];
                        @endphp
                        {{ $tzLabels[$appointmentData['timezone']] ?? $appointmentData['timezone'] }}
                    </span>
                </div>
                <div class="summary-item">
                    <span class="label">Tipo de Examen</span>
                    <span class="value">
                        {{ $appointmentData['medical_declaration']['exam_type'] == 'new' ? 'Dictamen Nuevo' : 'Renovacion' }}
                    </span>
                </div>
            </div>

            <!-- Medical Declaration Summary -->
            <div class="summary-card">
                <h4><i class="fas fa-file-medical"></i> Declaracion Medica</h4>
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                    <span class="document-status uploaded"><i class="fas fa-check-circle"></i> Completada</span>
                </div>
                <div class="summary-item">
                    <span class="label">Anos en el Mar</span>
                    <span class="value">{{ $appointmentData['medical_declaration']['years_at_sea'] }} anos</span>
                </div>
                <div class="summary-item">
                    <span class="label">Puesto Actual</span>
                    <span class="value">{{ $appointmentData['medical_declaration']['current_position'] }}</span>
                </div>
                <div class="summary-item">
                    <span class="label">Tipo de Embarcacion</span>
                    <span class="value">
                        @php
                            $vesselTypes = [
                                'cargo' => 'Buque de Carga',
                                'tanker' => 'Buque Tanque',
                                'passenger' => 'Buque de Pasajeros',
                                'fishing' => 'Buque Pesquero',
                                'offshore' => 'Plataforma Offshore',
                                'tugboat' => 'Remolcador',
                                'other' => 'Otro'
                            ];
                        @endphp
                        {{ $vesselTypes[$appointmentData['medical_declaration']['vessel_type']] ?? $appointmentData['medical_declaration']['vessel_type'] }}
                    </span>
                </div>
                @if(!empty($appointmentData['medical_declaration']['workplace_risks']))
                <div class="summary-item">
                    <span class="label">Riesgos Laborales</span>
                    <span class="value">
                        @php
                            $riskLabels = [
                                'noise' => 'Ruido',
                                'dust' => 'Polvo',
                                'chemicals' => 'Quimicos',
                                'vibration' => 'Vibracion',
                                'heights' => 'Alturas',
                                'confined_spaces' => 'Espacios Confinados'
                            ];
                            $risks = $appointmentData['medical_declaration']['workplace_risks'];
                            $riskNames = array_map(fn($r) => $riskLabels[$r] ?? $r, $risks);
                        @endphp
                        {{ implode(', ', $riskNames) }}
                    </span>
                </div>
                @endif
            </div>

            <!-- Uploaded Documents -->
            <div class="summary-card">
                <h4><i class="fas fa-folder-open"></i> Documentos Subidos</h4>
                @foreach($documents as $document)
                <div class="summary-item">
                    <span class="label">{{ $document->document_type_label }}</span>
                    <span class="value" style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-check-circle" style="color: #28a745;"></i>
                        {{ $document->original_name }}
                    </span>
                </div>
                @endforeach
            </div>

            <!-- Price Summary -->
            <div class="price-summary">
                <h4><i class="fas fa-receipt"></i> Detalle del Cargo</h4>
                <div class="price-row">
                    <span>Dictamen Medico {{ $appointmentData['medical_declaration']['exam_type'] == 'new' ? 'Nuevo' : 'Renovacion' }}</span>
                    <span>${{ number_format($serviceCost['subtotal'], 2) }} MXN</span>
                </div>
                <div class="price-row">
                    <span>IVA ({{ $serviceCost['tax_rate'] }}%)</span>
                    <span>${{ number_format($serviceCost['tax'], 2) }} MXN</span>
                </div>
                <div class="price-row total">
                    <span>Total</span>
                    <span class="amount">${{ number_format($serviceCost['total'], 2) }} MXN</span>
                </div>
            </div>

            <!-- Notice -->
            <div style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px; padding: 1rem; display: flex; align-items: flex-start; gap: 0.75rem;">
                <i class="fas fa-info-circle" style="color: #856404; font-size: 1.2rem; margin-top: 2px;"></i>
                <div>
                    <strong style="color: #856404;">Importante</strong>
                    <p style="color: #856404; margin: 0.25rem 0 0 0; font-size: 0.9rem;">
                        Al hacer clic en "Proceder al Pago", su espacio quedara reservado temporalmente por 10 minutos
                        mientras completa el proceso de pago.
                    </p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <form action="{{ route('appointments.step4.process') }}" method="POST">
            @csrf
            <div class="step-navigation">
                <a href="{{ route('appointments.step3') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Atras
                </a>
                <button type="submit" class="btn-next" style="background: linear-gradient(135deg, #28a745 0%, #218838 100%);">
                    <i class="fas fa-credit-card"></i> Proceder al Pago
                </button>
            </div>
        </form>
    </div>
</section>
@endsection
