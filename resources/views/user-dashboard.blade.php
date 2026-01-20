<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Panel de Usuario - Medicina SEMAR</title>
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-logo">
                <img src="{{ asset('assets/img/SEMAR-1.png') }}" alt="SEMAR">
            </div>

            <nav class="sidebar-nav">
                <a href="#" class="active"><i class="fas fa-calendar-alt"></i> Citas</a>
                <a href="#"><i class="fas fa-history"></i> Historial de Citas</a>
                <a href="#"><i class="fas fa-user-circle"></i> Mi Perfil</a>
                <a href="#"><i class="fas fa-file-medical"></i> Declaracion Medica</a>
                <a href="#"><i class="fas fa-folder-open"></i> Mis Archivos</a>
                <a href="#"><i class="fas fa-certificate"></i> Mis Certificados</a>
                <a href="#"><i class="fas fa-receipt"></i> Recibos</a>
                <div class="nav-divider"></div>
                <a href="#"><i class="fas fa-key"></i> Cambio de Contrasena</a>
                <a href="#" class="logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Cerrar Sesion</a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="dash-header">
                <div class="user-welcome">
                    <img src="{{ asset('assets/img/user-avatar.jpg') }}" alt="Usuario" class="user-avatar">
                    <div class="welcome-text">
                        <span>Bienvenido de nuevo,</span>
                        <h2>{{ auth()->user()->name ?? 'Cap. Juan Perez' }}</h2>
                    </div>
                </div>
                <div class="timezone-selector">
                    <i class="fas fa-globe"></i>
                    <select id="timezone">
                        <option>Zona Central / Ciudad de Mexico (GMT-6)</option>
                        <option>Tiempo Universal Coordinado (UTC)</option>
                    </select>
                </div>
            </header>

            <section class="appointment-dashboard">

                <div class="hero-card">
                    <div class="hero-overlay">
                        <div class="hero-text-content">
                            <span class="badge-gold">Oficial & Seguro</span>
                            <h1>Medico virtual de medicina preventiva del transporte</h1>
                            <p>Reserva una cita online y realiza tu examen medico por videollamada desde cualquier parte del mundo.</p>
                            <a href="#" style="text-decoration: none;">
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
        </main>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('api.auth.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

</body>
</html>
