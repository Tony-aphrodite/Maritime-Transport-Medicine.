@extends('layouts.dashboard')

@section('title', 'Panel de Usuario')

@section('content')
<section class="appointment-dashboard">

    @if(auth()->user()->hasActiveAppointment())
    @php $activeAppointment = auth()->user()->getActiveAppointment(); @endphp
    <!-- Active Appointment Card -->
    <div class="active-appointment-card">
        <div class="appointment-card-header">
            <div class="appointment-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="appointment-header-text">
                <h3>Cita Activa</h3>
                <span class="appointment-status status-{{ $activeAppointment->status }}">
                    {{ $activeAppointment->status_label }}
                </span>
            </div>
        </div>
        <div class="appointment-card-body">
            <div class="appointment-detail">
                <i class="fas fa-calendar"></i>
                <div>
                    <span class="detail-label">Fecha</span>
                    <span class="detail-value">{{ $activeAppointment->appointment_date->format('d/m/Y') }}</span>
                </div>
            </div>
            <div class="appointment-detail">
                <i class="fas fa-clock"></i>
                <div>
                    <span class="detail-label">Hora</span>
                    <span class="detail-value">
                        @php
                            $hour = (int) substr($activeAppointment->appointment_time, 0, 2);
                            $display = sprintf('%d:00 %s', $hour > 12 ? $hour - 12 : $hour, $hour >= 12 ? 'PM' : 'AM');
                        @endphp
                        {{ $display }}
                    </span>
                </div>
            </div>
            <div class="appointment-detail">
                <i class="fas fa-stethoscope"></i>
                <div>
                    <span class="detail-label">Tipo de Examen</span>
                    <span class="detail-value">{{ $activeAppointment->exam_type == 'new' ? 'Nuevo' : 'Renovacion' }}</span>
                </div>
            </div>
            <div class="appointment-detail">
                <i class="fas fa-money-bill-wave"></i>
                <div>
                    <span class="detail-label">Total</span>
                    <span class="detail-value">${{ number_format($activeAppointment->total, 2) }} MXN</span>
                </div>
            </div>
        </div>
        <div class="appointment-card-footer">
            <button type="button" class="btn-cancel-appointment" id="btnCancelAppointment" data-appointment-id="{{ $activeAppointment->id }}">
                <i class="fas fa-times-circle"></i> Cancelar Cita
            </button>
        </div>
    </div>
    @endif

    <div class="hero-card">
        <div class="hero-overlay">
            <div class="hero-text-content">
                <span class="badge-gold">Oficial & Seguro</span>
                <h1>Medico virtual de medicina preventiva del transporte</h1>
                <p>Reserva una cita online y realiza tu examen medico por videollamada desde cualquier parte del mundo.</p>
                <button type="button" class="btn-primary-gold" id="btnRealizarCita">
                    <i class="fas fa-calendar-check"></i> Realizar Cita
                </button>
            </div>
        </div>
    </div>

    <!-- Profile Incomplete Modal -->
    <div id="profileIncompleteModal" class="modal-overlay" style="display: none;">
        <div class="modal-container">
            <div class="modal-header modal-header-warning">
                <h3><i class="fas fa-exclamation-triangle"></i> Perfil Incompleto</h3>
                <button type="button" class="modal-close" onclick="closeProfileModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div style="text-align: center; padding: 1rem 0;">
                    <i class="fas fa-user-edit" style="font-size: 3rem; color: #F59E0B; margin-bottom: 1rem;"></i>
                    <p style="font-size: 1.1rem; color: #374151; margin-bottom: 0.5rem;">
                        <strong>Debe completar su perfil antes de agendar una cita.</strong>
                    </p>
                    <p style="color: #6B7280;">
                        Por favor, complete todos los campos requeridos en su perfil para continuar con la reservacion.
                    </p>
                </div>
            </div>
            <div class="modal-footer" style="justify-content: center;">
                <a href="{{ route('profile.show') }}" class="btn-primary" style="text-decoration: none;">
                    <i class="fas fa-user-circle"></i> Ir a Mi Perfil
                </a>
            </div>
        </div>
    </div>

    <!-- Active Appointment Modal -->
    <div id="activeAppointmentModal" class="modal-overlay" style="display: none;">
        <div class="modal-container">
            <div class="modal-header modal-header-info">
                <h3><i class="fas fa-calendar-alt"></i> Cita Activa</h3>
                <button type="button" class="modal-close" onclick="closeActiveAppointmentModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div style="text-align: center; padding: 1rem 0;">
                    <i class="fas fa-calendar-check" style="font-size: 3rem; color: #3B82F6; margin-bottom: 1rem;"></i>
                    <p style="font-size: 1.1rem; color: #374151; margin-bottom: 0.5rem;">
                        <strong>Ya tiene una cita activa programada.</strong>
                    </p>
                    @if(auth()->user()->hasActiveAppointment())
                    @php $activeAppointment = auth()->user()->getActiveAppointment(); @endphp
                    <p style="color: #6B7280; margin-bottom: 0.5rem;">
                        <strong>Fecha:</strong> {{ $activeAppointment->appointment_date->format('d/m/Y') }}<br>
                        <strong>Hora:</strong> {{ $activeAppointment->appointment_time }}<br>
                        <strong>Estado:</strong> {{ $activeAppointment->status_label }}
                    </p>
                    @endif
                    <p style="color: #6B7280;">
                        No puede agendar otra cita hasta que esta sea completada o cancelada.
                    </p>
                </div>
            </div>
            <div class="modal-footer" style="justify-content: center;">
                <button type="button" class="btn-secondary" onclick="closeActiveAppointmentModal()">
                    Entendido
                </button>
            </div>
        </div>
    </div>

    <!-- Cancel Appointment Confirmation Modal -->
    <div id="cancelAppointmentModal" class="modal-overlay" style="display: none;">
        <div class="modal-container">
            <div class="modal-header modal-header-danger">
                <h3><i class="fas fa-exclamation-circle"></i> Cancelar Cita</h3>
                <button type="button" class="modal-close" onclick="closeCancelModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div style="text-align: center; padding: 1rem 0;">
                    <i class="fas fa-calendar-times" style="font-size: 3rem; color: #DC2626; margin-bottom: 1rem;"></i>
                    <p style="font-size: 1.1rem; color: #374151; margin-bottom: 0.5rem;">
                        <strong>¿Esta seguro de cancelar su cita?</strong>
                    </p>
                    <p style="color: #6B7280;">
                        Esta accion eliminara su cita y todos los documentos asociados.
                        Tendra que agendar una nueva cita si desea continuar con el proceso.
                    </p>
                </div>
            </div>
            <div class="modal-footer" style="justify-content: center; gap: 1rem;">
                <button type="button" class="btn-secondary" onclick="closeCancelModal()">
                    No, Mantener Cita
                </button>
                <button type="button" class="btn-danger" id="btnConfirmCancel">
                    <i class="fas fa-times"></i> Si, Cancelar Cita
                </button>
            </div>
        </div>
    </div>

    <div class="service-details-grid">

        <div class="detail-card">
            <div class="card-icon"><i class="fas fa-video"></i></div>
            <div class="card-info">
                <h4>Consulta en Linea</h4>
                <p>Sera conectado en el momento de la cita en una videollamada con el medico. Si lo prefiere, comuniquese con nosotros para concertar una videollamada en una <strong>plataforma alternativa</strong>.</p>
            </div>
        </div>

        <div class="detail-card">
            <div class="card-icon"><i class="fas fa-file-pdf"></i></div>
            <div class="card-info">
                <h4>Certificado Digital</h4>
                <p>Su certificado sera enviado por correo electronico al completar el examen. Puede descargarlo cuando lo desee a traves del acceso seguro a sus datos.</p>
            </div>
        </div>

        <div class="detail-card full-width">
            <div class="card-icon"><i class="fas fa-user-lock"></i></div>
            <div class="card-info">
                <h4>Seguridad e Inalterabilidad</h4>
                <p>Sus datos medicos se conservaran de forma segura sin necesidad de formularios en papel. Nuestros sistemas garantizan que su informacion sea <strong>inalterable e invulnerable</strong>.</p>
            </div>
        </div>

    </div>
</section>

<style>
    .modal-header-warning {
        background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%) !important;
    }
    .modal-header-info {
        background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%) !important;
    }
    .modal-header-danger {
        background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%) !important;
    }
    .btn-danger {
        background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-danger:hover {
        background: linear-gradient(135deg, #B91C1C 0%, #991B1B 100%);
        transform: translateY(-2px);
    }
    .btn-danger:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    /* Active Appointment Card Styles */
    .active-appointment-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
        overflow: hidden;
        border: 2px solid #3B82F6;
    }
    .appointment-card-header {
        background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        color: white;
    }
    .appointment-icon {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .appointment-header-text h3 {
        margin: 0 0 0.25rem 0;
        font-size: 1.25rem;
        font-weight: 700;
    }
    .appointment-status {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    .status-pending_payment {
        background: rgba(251, 191, 36, 0.2);
        color: #F59E0B;
    }
    .status-confirmed, .status-scheduled {
        background: rgba(16, 185, 129, 0.2);
        color: #10B981;
    }
    .appointment-card-body {
        padding: 1.5rem;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    .appointment-detail {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.75rem;
        background: #F8FAFC;
        border-radius: 10px;
    }
    .appointment-detail > i {
        color: #3B82F6;
        font-size: 1.1rem;
        margin-top: 0.15rem;
    }
    .appointment-detail .detail-label {
        display: block;
        font-size: 0.8rem;
        color: #6B7280;
        margin-bottom: 0.15rem;
    }
    .appointment-detail .detail-value {
        display: block;
        font-size: 1rem;
        font-weight: 600;
        color: #1F2937;
    }
    .appointment-card-footer {
        padding: 1rem 1.5rem;
        background: #F8FAFC;
        border-top: 1px solid #E5E7EB;
        text-align: center;
    }
    .btn-cancel-appointment {
        background: transparent;
        color: #DC2626;
        border: 2px solid #DC2626;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
    }
    .btn-cancel-appointment:hover {
        background: #DC2626;
        color: white;
        transform: translateY(-2px);
    }
    @media (max-width: 640px) {
        .appointment-card-body {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnRealizarCita = document.getElementById('btnRealizarCita');
    const profileCompleted = {{ auth()->user()->hasCompletedProfile() ? 'true' : 'false' }};
    const hasActiveAppointment = {{ auth()->user()->hasActiveAppointment() ? 'true' : 'false' }};

    btnRealizarCita.addEventListener('click', function() {
        if (!profileCompleted) {
            // Profile is incomplete, show profile modal
            document.getElementById('profileIncompleteModal').style.display = 'flex';
        } else if (hasActiveAppointment) {
            // User has active appointment, show active appointment modal
            document.getElementById('activeAppointmentModal').style.display = 'flex';
        } else {
            // Profile is complete and no active appointment, go to appointments
            window.location.href = '{{ route("appointments.step1") }}';
        }
    });

    // Cancel appointment button handler
    const btnCancelAppointment = document.getElementById('btnCancelAppointment');
    if (btnCancelAppointment) {
        btnCancelAppointment.addEventListener('click', function() {
            document.getElementById('cancelAppointmentModal').style.display = 'flex';
        });
    }

    // Confirm cancel button handler
    const btnConfirmCancel = document.getElementById('btnConfirmCancel');
    if (btnConfirmCancel) {
        btnConfirmCancel.addEventListener('click', function() {
            const appointmentId = document.getElementById('btnCancelAppointment').dataset.appointmentId;
            cancelAppointment(appointmentId);
        });
    }
});

function closeProfileModal() {
    document.getElementById('profileIncompleteModal').style.display = 'none';
}

function closeActiveAppointmentModal() {
    document.getElementById('activeAppointmentModal').style.display = 'none';
}

function closeCancelModal() {
    document.getElementById('cancelAppointmentModal').style.display = 'none';
}

function cancelAppointment(appointmentId) {
    const btnConfirmCancel = document.getElementById('btnConfirmCancel');
    const originalText = btnConfirmCancel.innerHTML;
    btnConfirmCancel.disabled = true;
    btnConfirmCancel.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cancelando...';

    fetch(`/appointments/${appointmentId}/cancel`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message and reload page
            closeCancelModal();
            alert(data.message);
            window.location.reload();
        } else {
            alert(data.message || 'Error al cancelar la cita.');
            btnConfirmCancel.disabled = false;
            btnConfirmCancel.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al conectar con el servidor. Por favor, intente de nuevo.');
        btnConfirmCancel.disabled = false;
        btnConfirmCancel.innerHTML = originalText;
    });
}

// Close modals when clicking outside
document.getElementById('profileIncompleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeProfileModal();
    }
});

document.getElementById('activeAppointmentModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeActiveAppointmentModal();
    }
});

const cancelModal = document.getElementById('cancelAppointmentModal');
if (cancelModal) {
    cancelModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeCancelModal();
        }
    });
}

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeProfileModal();
        closeActiveAppointmentModal();
        closeCancelModal();
    }
});
</script>
@endpush
