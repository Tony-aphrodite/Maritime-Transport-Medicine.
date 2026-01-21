<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel de Usuario') - Medicina SEMAR</title>
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
</head>
<body>

    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-logo">
                <img src="{{ asset('assets/img/SEMAR-1.png') }}" alt="SEMAR">
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="fas fa-calendar-alt"></i> Citas</a>
                <a href="#"><i class="fas fa-history"></i> Historial de Citas</a>
                <a href="{{ route('profile.show') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}"><i class="fas fa-user-circle"></i> Mi Perfil</a>
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
                        <h2>{{ auth()->user()->full_name ?? auth()->user()->name ?? 'Usuario' }}</h2>
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

            @yield('content')
        </main>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('api.auth.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    @stack('scripts')
</body>
</html>
