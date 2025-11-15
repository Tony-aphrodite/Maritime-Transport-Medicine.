<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MARINA - Guía de Acceso</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 100%);
            min-height: 100vh;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }
        .logo {
            color: #0f4c75;
            font-size: 3rem;
            margin-bottom: 10px;
        }
        .title {
            color: #1f2937;
            font-size: 2.2rem;
            font-weight: 700;
            margin: 0;
        }
        .subtitle {
            color: #6b7280;
            font-size: 1.1rem;
            margin: 5px 0;
        }
        .admin-section {
            background: linear-gradient(135deg, #dbeafe, #eff6ff);
            border: 2px solid #3b82f6;
            padding: 30px;
            border-radius: 12px;
            margin: 30px 0;
        }
        .admin-title {
            color: #1e40af;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .credentials {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #3b82f6;
        }
        .cred-item {
            display: flex;
            align-items: center;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
        }
        .cred-label {
            font-weight: 600;
            width: 120px;
            color: #374151;
        }
        .cred-value {
            background: #f3f4f6;
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            font-size: 0.95rem;
            color: #1f2937;
        }
        .steps {
            background: #f9fafb;
            padding: 25px;
            border-radius: 10px;
            margin: 25px 0;
        }
        .step {
            margin: 15px 0;
            padding: 15px;
            background: white;
            border-radius: 8px;
            border-left: 4px solid #10b981;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .step-number {
            background: #10b981;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
        }
        .link-button {
            display: inline-block;
            background: #3b82f6;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            margin: 10px 10px 10px 0;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        .link-button:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        }
        .primary-button {
            background: #10b981;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        .primary-button:hover {
            background: #059669;
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
        }
        .info-box {
            background: #fef3c7;
            border: 2px solid #f59e0b;
            padding: 20px;
            border-radius: 10px;
            margin: 25px 0;
            color: #92400e;
        }
        .feature-list {
            list-style: none;
            padding: 0;
        }
        .feature-list li {
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .feature-list li:last-child {
            border-bottom: none;
        }
        .check-icon {
            color: #10b981;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <i class="fas fa-anchor"></i>
            </div>
            <h1 class="title">Sistema MARINA</h1>
            <p class="subtitle">Portal de Acceso Unificado</p>
        </div>

        <div class="admin-section">
            <div class="admin-title">
                <i class="fas fa-shield-alt"></i>
                Acceso Administrativo
            </div>
            <p style="margin-bottom: 20px; color: #374151;">
                Use estas credenciales en la página principal de login para acceder al panel administrativo:
            </p>
            
            <div class="credentials">
                <div class="cred-item">
                    <span class="cred-label">Email:</span>
                    <span class="cred-value">AdminJuan@gmail.com</span>
                </div>
                <div class="cred-item">
                    <span class="cred-label">Password:</span>
                    <span class="cred-value">johnson@suceess!</span>
                </div>
            </div>

            <div class="steps">
                <h4 style="margin-bottom: 15px; color: #374151;">
                    <i class="fas fa-list-ol"></i> Pasos para Acceder:
                </h4>
                
                <div class="step">
                    <div class="step-number">1</div>
                    <div>Ir a la página de login principal</div>
                </div>
                
                <div class="step">
                    <div class="step-number">2</div>
                    <div>Ingresar email: <code>AdminJuan@gmail.com</code></div>
                </div>
                
                <div class="step">
                    <div class="step-number">3</div>
                    <div>Ingresar contraseña: <code>johnson@suceess!</code></div>
                </div>
                
                <div class="step">
                    <div class="step-number">4</div>
                    <div>Hacer clic en "Iniciar Sesión"</div>
                </div>
                
                <div class="step">
                    <div class="step-number">5</div>
                    <div>¡Será redirigido automáticamente al panel de administración!</div>
                </div>
            </div>
        </div>

        <div class="info-box">
            <strong><i class="fas fa-info-circle"></i> Importante:</strong>
            Ya no existe una página separada para admin login. Todo el acceso se maneja desde la página principal de login.
            Simplemente use las credenciales de administrador en /login para acceder al panel administrativo.
        </div>

        <h3 style="margin: 30px 0 20px; color: #374151;">
            <i class="fas fa-external-link-alt"></i> Enlaces Rápidos:
        </h3>
        
        <a href="/login" class="link-button primary-button">
            <i class="fas fa-sign-in-alt"></i> Ir a Login Principal
        </a>
        
        <a href="/admin/admin-status" class="link-button" target="_blank">
            <i class="fas fa-info-circle"></i> Ver Estado de Sesión
        </a>

        <h3 style="margin: 40px 0 20px; color: #374151;">
            <i class="fas fa-cogs"></i> Funcionalidades del Sistema:
        </h3>

        <ul class="feature-list">
            <li><i class="fas fa-check check-icon"></i> Login unificado desde página principal</li>
            <li><i class="fas fa-check check-icon"></i> Detección automática de credenciales de admin</li>
            <li><i class="fas fa-check check-icon"></i> Redirección automática al panel administrativo</li>
            <li><i class="fas fa-check check-icon"></i> Gestión de sesiones segura</li>
            <li><i class="fas fa-check check-icon"></i> Logout desde panel administrativo</li>
            <li><i class="fas fa-check check-icon"></i> Protección CSRF en todos los formularios</li>
            <li><i class="fas fa-check check-icon"></i> Audit logs de todos los intentos de login</li>
        </ul>

        <div style="margin-top: 40px; padding-top: 25px; border-top: 2px solid #e5e7eb; text-align: center; color: #6b7280;">
            <p><i class="fas fa-shield-alt"></i> Sistema de autenticación unificado listo para producción</p>
        </div>
    </div>
</body>
</html>