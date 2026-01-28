<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Cita #{{ $appointment->id }} - Panel de Administracion</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            background-attachment: fixed;
            color: #2d3748;
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Admin Header */
        .admin-header {
            background: linear-gradient(135deg, #0F4C75 0%, #3282B8 100%);
            color: white;
            padding: 1.5rem 2rem;
            box-shadow: 0 8px 32px rgba(15, 76, 117, 0.3);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .admin-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
        }

        .admin-logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-logo i {
            font-size: 2rem;
            color: #BBE1FA;
        }

        .admin-title {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .admin-nav {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .admin-nav a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
        }

        .admin-nav a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .admin-nav a.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        /* Content */
        .content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Back Button */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #0F4C75;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 1.5rem;
            transition: all 0.3s;
        }

        .back-link:hover {
            color: #3282B8;
        }

        /* Page Header */
        .page-header {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            border: 1px solid rgba(255,255,255,0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
        }

        .page-subtitle {
            color: #6b7280;
            font-size: 1rem;
        }

        /* Status Badge */
        .status-badge {
            padding: 0.5rem 1.25rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-badge.pending_payment {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge.confirmed {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-badge.completed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-badge.cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Cards */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: 1px solid rgba(255,255,255,0.3);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f3f4f6;
        }

        .card-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: white;
        }

        .card-icon.blue { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .card-icon.green { background: linear-gradient(135deg, #10b981, #059669); }
        .card-icon.purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
        .card-icon.cyan { background: linear-gradient(135deg, #06b6d4, #0891b2); }
        .card-icon.yellow { background: linear-gradient(135deg, #f59e0b, #d97706); }

        .card-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1f2937;
        }

        /* Info Row */
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #6b7280;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .info-value {
            color: #1f2937;
            font-weight: 600;
            text-align: right;
        }

        .info-value.highlight {
            color: #0F4C75;
            font-size: 1.125rem;
        }

        /* Medical Info */
        .medical-item {
            padding: 1rem;
            background: #f9fafb;
            border-radius: 10px;
            margin-bottom: 1rem;
        }

        .medical-item:last-child {
            margin-bottom: 0;
        }

        .medical-question {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .medical-answer {
            color: #6b7280;
        }

        .medical-answer.yes {
            color: #dc2626;
        }

        .medical-answer.no {
            color: #059669;
        }

        .medical-detail {
            margin-top: 0.5rem;
            padding: 0.75rem;
            background: white;
            border-radius: 8px;
            border-left: 3px solid #f59e0b;
            color: #92400e;
            font-size: 0.875rem;
        }

        /* Declaration Checkboxes */
        .declaration-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: #f9fafb;
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }

        .declaration-item:last-child {
            margin-bottom: 0;
        }

        .declaration-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
        }

        .declaration-icon.checked {
            background: #d1fae5;
            color: #059669;
        }

        .declaration-icon.unchecked {
            background: #fee2e2;
            color: #dc2626;
        }

        .declaration-text {
            flex: 1;
            font-size: 0.875rem;
            color: #4b5563;
        }

        /* Documents */
        .document-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .document-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: #f9fafb;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .document-item:hover {
            background: #f3f4f6;
        }

        .document-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #dbeafe;
            color: #1d4ed8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.125rem;
        }

        .document-info {
            flex: 1;
        }

        .document-name {
            font-weight: 600;
            color: #1f2937;
        }

        .document-meta {
            font-size: 0.75rem;
            color: #6b7280;
        }

        /* Buttons */
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0F4C75, #3282B8);
            color: white;
            box-shadow: 0 4px 15px rgba(15, 76, 117, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(15, 76, 117, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.8);
            color: #374151;
            border: 2px solid rgba(15, 76, 117, 0.2);
        }

        .btn-secondary:hover {
            background: white;
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
        }

        /* Actions Card */
        .actions-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: 1px solid rgba(255,255,255,0.3);
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
        }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            text-align: center;
        }

        .modal-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
        }

        .modal-icon.warning {
            background: #fef3c7;
            color: #f59e0b;
        }

        .modal-icon.danger {
            background: #fee2e2;
            color: #ef4444;
        }

        .modal-icon.success {
            background: #d1fae5;
            color: #10b981;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .modal-message {
            color: #6b7280;
            margin-bottom: 1.5rem;
        }

        .modal-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        /* Toast */
        .toast {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            color: white;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 1001;
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .toast.success {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .toast.error {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .content {
                padding: 1rem;
            }

            .admin-header-content {
                flex-direction: column;
                gap: 1rem;
            }

            .admin-nav {
                justify-content: center;
            }

            .page-header {
                flex-direction: column;
                text-align: center;
            }

            .card-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    @include('admin.partials.alert-system')

    <!-- Admin Header -->
    <header class="admin-header">
        <div class="admin-header-content">
            <div class="admin-logo">
                <i class="fas fa-shield-alt"></i>
                <div>
                    <div class="admin-title">Panel de Administracion</div>
                    <div style="font-size: 0.75rem; opacity: 0.8;">Sistema MARINA</div>
                </div>
            </div>
            <nav class="admin-nav">
                <a href="/admin/dashboard"><i class="fas fa-chart-line"></i> Dashboard</a>
                <a href="/admin/appointments" class="active"><i class="fas fa-calendar-check"></i> Citas</a>
                <a href="/admin/audit-logs"><i class="fas fa-clipboard-list"></i> Audit Logs</a>
                <a href="/admin/users"><i class="fas fa-users"></i> Usuarios</a>
                <a href="/admin/settings"><i class="fas fa-cog"></i> Configuracion</a>
                <a href="/admin/logout" style="background: rgba(239, 68, 68, 0.2); color: #fca5a5;"><i class="fas fa-sign-out-alt"></i> Cerrar Sesion</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <div class="content">
        <!-- Back Link -->
        <a href="{{ route('admin.appointments.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Volver a la lista de citas
        </a>

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Cita #{{ $appointment->id }}</h1>
                <p class="page-subtitle">Creada el {{ $appointment->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <span class="status-badge {{ $appointment->status }}">
                @if($appointment->status == 'pending_payment')
                    <i class="fas fa-clock"></i> Pendiente de Pago
                @elseif($appointment->status == 'confirmed')
                    <i class="fas fa-check"></i> Confirmada
                @elseif($appointment->status == 'completed')
                    <i class="fas fa-check-double"></i> Completada
                @else
                    <i class="fas fa-times"></i> Cancelada
                @endif
            </span>
        </div>

        <!-- Cards Grid -->
        <div class="card-grid">
            <!-- User Info -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon blue">
                        <i class="fas fa-user"></i>
                    </div>
                    <h2 class="card-title">Informacion del Usuario</h2>
                </div>
                <div class="info-row">
                    <span class="info-label">Nombre Completo</span>
                    <span class="info-value">
                        {{ $appointment->user->nombres ?? 'N/A' }}
                        {{ $appointment->user->apellido_paterno ?? '' }}
                        {{ $appointment->user->apellido_materno ?? '' }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $appointment->user->email ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">CURP</span>
                    <span class="info-value">{{ $appointment->user->curp ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Telefono</span>
                    <span class="info-value">{{ $appointment->user->telefono_movil ?? 'N/A' }}</span>
                </div>
            </div>

            <!-- Appointment Info -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon cyan">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h2 class="card-title">Detalles de la Cita</h2>
                </div>
                <div class="info-row">
                    <span class="info-label">Fecha</span>
                    <span class="info-value highlight">{{ $appointment->appointment_date->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Hora</span>
                    <span class="info-value highlight">{{ $appointment->appointment_time }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tipo de Examen</span>
                    <span class="info-value">{{ $appointment->exam_type_label }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Zona Horaria</span>
                    <span class="info-value">{{ $appointment->timezone ?? 'America/Mexico_City' }}</span>
                </div>
            </div>

            <!-- Professional Info -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon purple">
                        <i class="fas fa-anchor"></i>
                    </div>
                    <h2 class="card-title">Informacion Profesional</h2>
                </div>
                <div class="info-row">
                    <span class="info-label">Anos en el Mar</span>
                    <span class="info-value">{{ $appointment->years_at_sea ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Puesto Actual</span>
                    <span class="info-value">{{ $appointment->current_position ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tipo de Embarcacion</span>
                    <span class="info-value">{{ $appointment->vessel_type ?? 'N/A' }}</span>
                </div>
                @if($appointment->workplace_risks && count($appointment->workplace_risks) > 0)
                <div class="info-row">
                    <span class="info-label">Riesgos Laborales</span>
                    <span class="info-value">{{ implode(', ', $appointment->workplace_risks) }}</span>
                </div>
                @endif
            </div>

            <!-- Payment Info -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon green">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h2 class="card-title">Informacion de Pago</h2>
                </div>
                <div class="info-row">
                    <span class="info-label">Subtotal</span>
                    <span class="info-value">${{ number_format($appointment->subtotal, 2) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">IVA</span>
                    <span class="info-value">${{ number_format($appointment->tax, 2) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Total</span>
                    <span class="info-value highlight">${{ number_format($appointment->total, 2) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Estado de Pago</span>
                    <span class="info-value">
                        @if($appointment->payment_status == 'paid')
                            <span style="color: #059669;"><i class="fas fa-check-circle"></i> Pagado</span>
                        @else
                            <span style="color: #f59e0b;"><i class="fas fa-clock"></i> Pendiente</span>
                        @endif
                    </span>
                </div>
                @if($appointment->payment_date)
                <div class="info-row">
                    <span class="info-label">Fecha de Pago</span>
                    <span class="info-value">{{ $appointment->payment_date->format('d/m/Y H:i') }}</span>
                </div>
                @endif
                @if($appointment->payment_reference)
                <div class="info-row">
                    <span class="info-label">Referencia</span>
                    <span class="info-value">{{ $appointment->payment_reference }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Medical Declaration -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <div class="card-icon yellow">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <h2 class="card-title">Declaracion Medica</h2>
            </div>

            <div class="medical-item">
                <div class="medical-question">
                    <i class="fas fa-stethoscope"></i>
                    Condiciones cronicas
                </div>
                <div class="medical-answer {{ $appointment->has_chronic_conditions ? 'yes' : 'no' }}">
                    {{ $appointment->has_chronic_conditions ? 'Si' : 'No' }}
                </div>
                @if($appointment->has_chronic_conditions && $appointment->chronic_conditions_detail)
                    <div class="medical-detail">{{ $appointment->chronic_conditions_detail }}</div>
                @endif
            </div>

            <div class="medical-item">
                <div class="medical-question">
                    <i class="fas fa-pills"></i>
                    Toma medicamentos
                </div>
                <div class="medical-answer {{ $appointment->takes_medications ? 'yes' : 'no' }}">
                    {{ $appointment->takes_medications ? 'Si' : 'No' }}
                </div>
                @if($appointment->takes_medications && $appointment->medications_detail)
                    <div class="medical-detail">{{ $appointment->medications_detail }}</div>
                @endif
            </div>

            <div class="medical-item">
                <div class="medical-question">
                    <i class="fas fa-allergies"></i>
                    Tiene alergias
                </div>
                <div class="medical-answer {{ $appointment->has_allergies ? 'yes' : 'no' }}">
                    {{ $appointment->has_allergies ? 'Si' : 'No' }}
                </div>
                @if($appointment->has_allergies && $appointment->allergies_detail)
                    <div class="medical-detail">{{ $appointment->allergies_detail }}</div>
                @endif
            </div>

            <div class="medical-item">
                <div class="medical-question">
                    <i class="fas fa-user-md"></i>
                    Cirugias previas
                </div>
                <div class="medical-answer {{ $appointment->has_surgeries ? 'yes' : 'no' }}">
                    {{ $appointment->has_surgeries ? 'Si' : 'No' }}
                </div>
                @if($appointment->has_surgeries && $appointment->surgeries_detail)
                    <div class="medical-detail">{{ $appointment->surgeries_detail }}</div>
                @endif
            </div>

            @if($appointment->additional_notes)
            <div class="medical-item">
                <div class="medical-question">
                    <i class="fas fa-sticky-note"></i>
                    Notas adicionales
                </div>
                <div class="medical-detail" style="border-left-color: #3b82f6; color: #1e40af;">
                    {{ $appointment->additional_notes }}
                </div>
            </div>
            @endif
        </div>

        <!-- Declarations -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <div class="card-icon purple">
                    <i class="fas fa-file-signature"></i>
                </div>
                <h2 class="card-title">Declaraciones y Consentimientos</h2>
            </div>

            <div class="declaration-item">
                <div class="declaration-icon {{ $appointment->declaration_truthful ? 'checked' : 'unchecked' }}">
                    <i class="fas {{ $appointment->declaration_truthful ? 'fa-check' : 'fa-times' }}"></i>
                </div>
                <span class="declaration-text">Declaro que toda la informacion proporcionada es verdadera</span>
            </div>

            <div class="declaration-item">
                <div class="declaration-icon {{ $appointment->declaration_terms ? 'checked' : 'unchecked' }}">
                    <i class="fas {{ $appointment->declaration_terms ? 'fa-check' : 'fa-times' }}"></i>
                </div>
                <span class="declaration-text">Acepto los terminos y condiciones del servicio</span>
            </div>

            <div class="declaration-item">
                <div class="declaration-icon {{ $appointment->declaration_privacy ? 'checked' : 'unchecked' }}">
                    <i class="fas {{ $appointment->declaration_privacy ? 'fa-check' : 'fa-times' }}"></i>
                </div>
                <span class="declaration-text">Acepto el aviso de privacidad</span>
            </div>

            <div class="declaration-item">
                <div class="declaration-icon {{ $appointment->declaration_consent ? 'checked' : 'unchecked' }}">
                    <i class="fas {{ $appointment->declaration_consent ? 'fa-check' : 'fa-times' }}"></i>
                </div>
                <span class="declaration-text">Autorizo el uso de mis datos para el examen medico</span>
            </div>
        </div>

        <!-- Documents -->
        @if($appointment->documents && $appointment->documents->count() > 0)
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <div class="card-icon cyan">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h2 class="card-title">Documentos Subidos</h2>
            </div>

            <div class="document-list">
                @foreach($appointment->documents as $document)
                <div class="document-item">
                    <div class="document-icon">
                        <i class="fas fa-file-{{ Str::contains($document->file_type, 'pdf') ? 'pdf' : 'image' }}"></i>
                    </div>
                    <div class="document-info">
                        <div class="document-name">{{ $document->original_name ?? $document->document_type }}</div>
                        <div class="document-meta">
                            {{ $document->document_type }} -
                            {{ $document->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="actions-card">
            @if($appointment->status == 'pending_payment')
                <button class="btn btn-success" onclick="changeStatus('confirmed')">
                    <i class="fas fa-check"></i> Confirmar Pago
                </button>
                <button class="btn btn-danger" onclick="changeStatus('cancelled')">
                    <i class="fas fa-times"></i> Cancelar Cita
                </button>
            @elseif($appointment->status == 'confirmed')
                <button class="btn btn-primary" onclick="changeStatus('completed')">
                    <i class="fas fa-check-double"></i> Marcar como Completada
                </button>
                <button class="btn btn-danger" onclick="changeStatus('cancelled')">
                    <i class="fas fa-times"></i> Cancelar Cita
                </button>
            @else
                <span style="color: #6b7280;">
                    <i class="fas fa-info-circle"></i>
                    No hay acciones disponibles para esta cita
                </span>
            @endif

            <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver a la Lista
            </a>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal-overlay" id="confirmModal">
        <div class="modal-content">
            <div class="modal-icon warning" id="modalIcon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="modal-title" id="modalTitle">Confirmar Accion</h3>
            <p class="modal-message" id="modalMessage">Esta seguro de realizar esta accion?</p>
            <div class="modal-buttons">
                <button class="btn btn-secondary" onclick="closeModal()">Cancelar</button>
                <button class="btn btn-primary" id="confirmBtn" onclick="confirmAction()">Confirmar</button>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div class="toast" id="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toastMessage"></span>
    </div>

    <script>
        let pendingStatus = null;

        function changeStatus(newStatus) {
            const statusMessages = {
                'confirmed': {
                    title: 'Confirmar Pago',
                    message: 'Esta seguro de confirmar el pago de esta cita? Se marcara como pagada.',
                    icon: 'success',
                    iconHtml: '<i class="fas fa-check-circle"></i>'
                },
                'completed': {
                    title: 'Completar Cita',
                    message: 'Esta seguro de marcar esta cita como completada?',
                    icon: 'success',
                    iconHtml: '<i class="fas fa-check-double"></i>'
                },
                'cancelled': {
                    title: 'Cancelar Cita',
                    message: 'Esta seguro de cancelar esta cita? Esta accion no se puede deshacer.',
                    icon: 'danger',
                    iconHtml: '<i class="fas fa-times-circle"></i>'
                }
            };

            const config = statusMessages[newStatus];
            if (!config) return;

            pendingStatus = newStatus;

            const modal = document.getElementById('confirmModal');
            const modalIcon = document.getElementById('modalIcon');
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');

            modalIcon.className = 'modal-icon ' + config.icon;
            modalIcon.innerHTML = config.iconHtml;
            modalTitle.textContent = config.title;
            modalMessage.textContent = config.message;

            modal.classList.add('active');
        }

        function closeModal() {
            document.getElementById('confirmModal').classList.remove('active');
            pendingStatus = null;
        }

        async function confirmAction() {
            if (!pendingStatus) return;

            closeModal();

            try {
                const response = await fetch(`/admin/appointments/{{ $appointment->id }}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ status: pendingStatus })
                });

                const data = await response.json();

                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message || 'Error al actualizar el estado', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error de conexion', 'error');
            }
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');

            toast.className = 'toast ' + type;
            toast.querySelector('i').className = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
            toastMessage.textContent = message;

            toast.classList.add('show');

            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        // Close modal on outside click
        document.getElementById('confirmModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>
