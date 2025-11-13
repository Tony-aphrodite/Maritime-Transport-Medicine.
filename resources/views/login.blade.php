<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MARINA - Portal de Acceso</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #0F4C75;
            --primary-dark: #0A3A5C;
            --secondary-color: #3282B8;
            --accent-color: #BBE1FA;
            --success-color: #10B981;
            --warning-color: #F59E0B;
            --error-color: #EF4444;
            --text-primary: #1F2937;
            --text-secondary: #6B7280;
            --background-light: #F8FAFC;
            --background-white: #FFFFFF;
            --border-light: #E5E7EB;
            --border-focus: #3B82F6;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, var(--background-light) 0%, #E0F2FE 100%);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
            font-feature-settings: 'kern' 1, 'liga' 1;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .background-pattern {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(15, 76, 117, 0.1) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
        }

        .header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-md);
            backdrop-filter: blur(10px);
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        .logo svg {
            width: 32px;
            height: 32px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }

        .title {
            font-size: 1.25rem;
            font-weight: 600;
            letter-spacing: -0.025em;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }

        .nav-links a:hover {
            background-color: rgba(255,255,255,0.15);
            transform: translateY(-1px);
        }

        .main-content {
            min-height: calc(100vh - 80px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }

        .login-container {
            background: var(--background-white);
            border-radius: 20px;
            box-shadow: var(--shadow-xl);
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            border: 1px solid var(--border-light);
            backdrop-filter: blur(10px);
        }

        .brand-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 1rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-md);
        }

        .brand-logo svg {
            width: 32px;
            height: 32px;
            color: white;
        }

        .brand-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
            letter-spacing: -0.025em;
        }

        .brand-subtitle {
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .welcome-section {
            margin-bottom: 2rem;
            text-align: center;
        }

        .welcome-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .welcome-subtitle {
            font-size: 0.9rem;
            color: var(--text-secondary);
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .input-container {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 1rem;
            z-index: 1;
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.75rem;
            border: 2px solid var(--border-light);
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background-color: var(--background-white);
            font-weight: 400;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            background-color: var(--background-white);
        }

        .form-input::placeholder {
            color: var(--text-secondary);
            font-weight: 400;
        }

        .login-btn {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .login-btn:hover::before {
            left: 100%;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            gap: 1rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .checkbox {
            width: 1rem;
            height: 1rem;
            border: 2px solid var(--border-light);
            border-radius: 4px;
            cursor: pointer;
        }

        .checkbox:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .checkbox-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            cursor: pointer;
        }

        .forgot-link {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .forgot-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--border-light);
        }

        .divider span {
            background: var(--background-white);
            padding: 0 1rem;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .register-section {
            text-align: center;
            padding: 1.5rem;
            background: var(--background-light);
            border-radius: 12px;
            border: 1px solid var(--border-light);
        }

        .register-text {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-bottom: 0.75rem;
        }

        .register-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .register-link:hover {
            color: var(--primary-dark);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
                padding: 1rem;
            }

            .nav-links {
                gap: 1rem;
            }

            .main-content {
                padding: 1rem;
            }

            .login-container {
                padding: 2rem;
            }

            .welcome-title {
                font-size: 1.5rem;
            }

            .form-options {
                flex-direction: column;
                align-items: stretch;
                gap: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 1.5rem;
                border-radius: 16px;
            }

            .brand-section {
                margin-bottom: 1.5rem;
            }

            .welcome-section {
                margin-bottom: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="background-pattern"></div>
    
    <!-- Header -->
    <header class="header">
        <div class="logo-section">
            <div class="logo">
                <i class="fas fa-anchor" style="color: white; font-size: 1.5rem;"></i>
            </div>
            <div class="title">MARINA - Secretaría de Marina</div>
        </div>
        <nav class="nav-links">
            <a href="#tramites">Trámites</a>
            <a href="#gobierno">Gobierno</a>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="login-container">
            <!-- Brand Section -->
            <div class="brand-section">
                <div class="brand-logo">
                    <i class="fas fa-user-md" style="color: white; font-size: 1.5rem;"></i>
                </div>
                <div class="brand-title">Certificación Médica</div>
                <div class="brand-subtitle">Sistema Digital de Medicina Marítima</div>
            </div>
            
            <!-- Welcome Section -->
            <div class="welcome-section">
                <h1 class="welcome-title">Bienvenido de vuelta</h1>
                <p class="welcome-subtitle">Ingresa a tu cuenta para continuar</p>
            </div>
            
            <!-- Login Form -->
            <form action="/dashboard" method="GET">
                <div class="form-group">
                    <label class="form-label" for="email">Correo electrónico</label>
                    <div class="input-container">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="email" class="form-input" placeholder="ejemplo@marina.gob.mx" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="password">Contraseña</label>
                    <div class="input-container">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="password" class="form-input" placeholder="Ingresa tu contraseña" required>
                    </div>
                </div>
                
                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" class="checkbox">
                        <label for="remember" class="checkbox-label">Recordarme</label>
                    </div>
                    <a href="#recovery" class="forgot-link">¿Olvidaste tu contraseña?</a>
                </div>
                
                <button type="submit" class="login-btn">
                    <span>Iniciar Sesión</span>
                </button>
            </form>
            
            <div class="divider">
                <span>¿No tienes cuenta?</span>
            </div>
            
            <div class="register-section">
                <p class="register-text">¿Eres nuevo en el sistema?</p>
                <a href="/registro" class="register-link">Crear nueva cuenta</a>
            </div>
        </div>
    </main>
</body>
</html>