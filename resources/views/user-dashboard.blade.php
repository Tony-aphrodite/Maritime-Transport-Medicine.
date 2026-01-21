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
                <a href="{{ route('appointments.step1') }}" style="text-decoration: none;">
                    <button class="btn-primary-gold">
                        <i class="fas fa-calendar-check"></i> Realizar Cita
                    </button>
                </a>
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
@endsection
