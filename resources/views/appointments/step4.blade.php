@extends('layouts.dashboard')

@section('title', 'Agendar Cita - Confirmacion')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/appointments.css') }}">
@endpush

@section('content')
<section class="booking-container">
    <!-- Back Navigation -->
    <div class="back-nav">
        <a href="{{ route('appointments.step3') }}" class="btn-back-link">
            <i class="fas fa-arrow-left"></i> Volver a Declaracion
        </a>
    </div>

    <!-- Stepper -->
    <div class="stepper">
        <div class="step completed"><span><i class="fas fa-check"></i></span><p>Fecha</p></div>
        <div class="step completed"><span><i class="fas fa-check"></i></span><p>Archivos</p></div>
        <div class="step completed"><span><i class="fas fa-check"></i></span><p>Declaracion</p></div>
        <div class="step active"><span>4</span><p>Confirmacion</p></div>
        <div class="step"><span>5</span><p>Pago</p></div>
    </div>

    <div class="confirmation-layout">
        <!-- Left Section - Info Review -->
        <div class="info-review-section">
            <div class="card-white">
                <h3><i class="fas fa-user-check"></i> Verifique su Informacion</h3>
                <p class="subtitle">Confirme que sus datos personales son correctos para la emision del certificado medico.</p>

                <div class="data-profile-grid">
                    <div class="data-item">
                        <label>Nombre Completo</label>
                        <p>{{ $user->full_name ?? $user->name }}</p>
                    </div>
                    <div class="data-item">
                        <label>Correo Electronico</label>
                        <p>{{ $user->email }}</p>
                    </div>
                    @if($user->telefono_movil)
                    <div class="data-item">
                        <label>Telefono</label>
                        <p>{{ $user->telefono_movil }}</p>
                    </div>
                    @endif
                    <div class="data-item">
                        <label>Tipo de Examen</label>
                        <p>{{ $appointmentData['medical_declaration']['exam_type'] == 'new' ? 'Dictamen Nuevo' : 'Renovacion' }}</p>
                    </div>
                </div>

                <div class="declaration-status">
                    <i class="fas fa-file-medical"></i>
                    <div>
                        <h4>Declaracion Medica</h4>
                        <p>Su formulario de salud ha sido cargado correctamente.</p>
                    </div>
                    <span class="badge-success">Completado</span>
                </div>

                <div class="declaration-summary-mini">
                    <div class="mini-status-item">
                        <span><i class="fas fa-history"></i> Tipo de Examen:</span>
                        <strong>{{ $appointmentData['medical_declaration']['exam_type'] == 'new' ? 'Nuevo' : 'Renovacion' }}</strong>
                    </div>
                    <div class="mini-status-item">
                        <span><i class="fas fa-anchor"></i> Tiempo en Mar:</span>
                        <strong>{{ $appointmentData['medical_declaration']['years_at_sea'] }} anos</strong>
                    </div>
                    @if(!empty($appointmentData['medical_declaration']['workplace_risks']))
                    <div class="mini-status-item">
                        <span><i class="fas fa-exclamation-triangle"></i> Riesgos:</span>
                        <strong>
                            @php
                                $riskLabels = [
                                    'none' => 'Ninguno',
                                    'noise' => 'Ruido',
                                    'dust' => 'Polvo',
                                    'radiation' => 'Radiacion',
                                    'other' => 'Otro'
                                ];
                                $risks = $appointmentData['medical_declaration']['workplace_risks'];
                                $riskNames = array_map(fn($r) => $riskLabels[$r] ?? $r, $risks);
                            @endphp
                            {{ implode(', ', $riskNames) }}
                        </strong>
                    </div>
                    @endif
                </div>

                <!-- Uploaded Documents Summary -->
                @if($documents->count() > 0)
                <div class="documents-summary">
                    <h4 style="color: var(--primary-dark); margin: 20px 0 15px 0; font-size: 1rem;">
                        <i class="fas fa-folder-open"></i> Documentos Subidos ({{ $documents->count() }})
                    </h4>
                    <div class="docs-list">
                        @foreach($documents as $document)
                        <div class="doc-item">
                            <i class="fas fa-check-circle" style="color: #28a745;"></i>
                            <span>{{ $document->document_type_label }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Right Section - Summary Card -->
        <aside class="summary-section">
            <div class="summary-card-dark">
                <h4>Detalles de la Cita</h4>
                <div class="detail-row">
                    <span>Fecha:</span>
                    <strong>
                        @php
                            $date = \Carbon\Carbon::parse($appointmentData['date']);
                            $dayNames = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
                            $monthNames = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                        @endphp
                        {{ $dayNames[$date->dayOfWeek] }}, {{ $date->day }} de {{ $monthNames[$date->month - 1] }}
                    </strong>
                </div>
                <div class="detail-row">
                    <span>Hora:</span>
                    <strong>
                        @php
                            $hour = (int) substr($appointmentData['time'], 0, 2);
                            $display = sprintf('%d:00 %s', $hour > 12 ? $hour - 12 : $hour, $hour >= 12 ? 'PM' : 'AM');
                        @endphp
                        {{ $display }}
                    </strong>
                </div>
                <div class="detail-row">
                    <span>Zona Horaria:</span>
                    <strong>
                        @php
                            $tzLabels = [
                                'America/Mexico_City' => 'CDMX (GMT-6)',
                                'America/Tijuana' => 'Tijuana (GMT-8)',
                                'America/Cancun' => 'Cancun (GMT-5)',
                                'UTC' => 'UTC'
                            ];
                        @endphp
                        {{ $tzLabels[$appointmentData['timezone']] ?? $appointmentData['timezone'] }}
                    </strong>
                </div>
                <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.1); margin: 20px 0;">
                <div class="price-row">
                    <span>Costo del Servicio:</span>
                    <span class="price">${{ number_format($serviceCost['total'], 2) }} MXN</span>
                </div>

                <p class="notice-text">Al hacer clic en pagar, su espacio quedara reservado temporalmente por 10 minutos.</p>

                <form action="{{ route('appointments.step4.process') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-primary-gold w-100">
                        Proceder al Pago <i class="fas fa-credit-card"></i>
                    </button>
                </form>
            </div>
        </aside>
    </div>
</section>
@endsection
