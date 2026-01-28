@extends('layouts.dashboard')

@section('title', 'Panel de Usuario')

@section('content')
<section class="appointment-dashboard">

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
});

function closeProfileModal() {
    document.getElementById('profileIncompleteModal').style.display = 'none';
}

function closeActiveAppointmentModal() {
    document.getElementById('activeAppointmentModal').style.display = 'none';
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

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeProfileModal();
        closeActiveAppointmentModal();
    }
});
</script>
@endpush
