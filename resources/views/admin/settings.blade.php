<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración - Panel de Administración</title>
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

        .main-container {
            background: transparent;
            min-height: 100vh;
            margin-top: 0;
        }

        /* Maritime admin header */
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

        .content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            border: 1px solid rgba(255,255,255,0.3);
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: #6b7280;
            font-size: 1.125rem;
        }

        .placeholder-content {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 3rem 2rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            border: 1px solid rgba(255,255,255,0.3);
            text-align: center;
        }

        .placeholder-icon {
            font-size: 4rem;
            color: #0F4C75;
            margin-bottom: 2rem;
        }

        .placeholder-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1rem;
        }

        .placeholder-description {
            color: #6b7280;
            font-size: 1.125rem;
            max-width: 600px;
            margin: 0 auto 2rem;
            line-height: 1.6;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
            max-width: 500px;
            margin: 0 auto;
        }

        .feature-list li {
            padding: 0.75rem 0;
            color: #4b5563;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .feature-list li::before {
            content: '⚙️';
            font-size: 1.25rem;
        }

        .alert-demo {
            background: rgba(16, 185, 129, 0.1);
            border: 2px solid #10b981;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .alert-demo-title {
            color: #059669;
            font-weight: 700;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .test-button {
            background: linear-gradient(135deg, #0F4C75 0%, #3282B8 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 1rem;
        }

        .test-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(15, 76, 117, 0.3);
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
                    <div class="admin-title">Panel de Administración</div>
                    <div style="font-size: 0.75rem; opacity: 0.8;">Sistema MARINA</div>
                </div>
            </div>
            <nav class="admin-nav">
                <a href="/admin/dashboard"><i class="fas fa-chart-line"></i> Dashboard</a>
                <a href="/admin/audit-logs"><i class="fas fa-clipboard-list"></i> Audit Logs</a>
                <a href="/admin/users"><i class="fas fa-users"></i> Usuarios</a>
                <a href="/admin/settings" class="active"><i class="fas fa-cog"></i> Configuración</a>
                <a href="/admin/logout" style="background: rgba(239, 68, 68, 0.2); color: #fca5a5;"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
                <a href="/"><i class="fas fa-arrow-left"></i> Volver</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <div class="main-container">
        <div class="content">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Configuración del Sistema</h1>
                <p class="page-subtitle">Ajustes y configuraciones del sistema MARINA</p>
            </div>

            <!-- Placeholder Content -->
            <div class="placeholder-content">
                <div class="placeholder-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <h2 class="placeholder-title">Panel de Configuración en Desarrollo</h2>
                <p class="placeholder-description">
                    Esta sección permitirá configurar todos los aspectos del sistema de Transporte Marítimo y Medicina.
                </p>

                <ul class="feature-list">
                    <li>Configuración de APIs (VerificaMex, face verification)</li>
                    <li>Ajustes de autenticación y seguridad</li>
                    <li>Configuración de alertas y notificaciones</li>
                    <li>Personalización de reportes y auditorías</li>
                    <li>Gestión de permisos y roles de usuario</li>
                    <li>Configuración de backup y mantenimiento</li>
                    <li>Ajustes de rendimiento del sistema</li>
                    <li>Configuración de integración con sistemas externos</li>
                </ul>

                <div class="alert-demo">
                    <div class="alert-demo-title">
                        <i class="fas fa-bell"></i>
                        Sistema de Alertas en Tiempo Real
                    </div>
                    <p style="margin-bottom: 1rem; color: #4b5563;">
                        Las alertas funcionan en todas las páginas del panel administrativo. 
                        Haz clic para probar una alerta de demostración:
                    </p>
                    <button class="test-button" onclick="testAdminAlert()">
                        <i class="fas fa-test-tube"></i> Probar Alerta
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>