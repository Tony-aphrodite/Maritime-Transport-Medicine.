<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - MARINA</title>
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

        .reset-container {
            background: var(--background-white);
            border-radius: 20px;
            box-shadow: var(--shadow-xl);
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            border: 1px solid var(--border-light);
            backdrop-filter: blur(10px);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            transition: color 0.2s ease;
        }

        .back-link:hover {
            color: var(--primary-color);
        }

        .icon-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .icon-container {
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

        .title-section {
            margin-bottom: 2rem;
            text-align: center;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .page-subtitle {
            font-size: 0.9rem;
            color: var(--text-secondary);
            font-weight: 400;
            line-height: 1.5;
        }

        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--success-color);
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
            color: #065f46;
            font-size: 0.875rem;
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

        .submit-btn {
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

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .help-section {
            text-align: center;
            padding: 1.5rem;
            background: var(--background-light);
            border-radius: 12px;
            border: 1px solid var(--border-light);
        }

        .help-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .help-text {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
            line-height: 1.5;
        }

        .help-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .help-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            transition: color 0.2s ease;
        }

        .help-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
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

            .reset-container {
                padding: 2rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .help-links {
                flex-direction: column;
                gap: 0.5rem;
            }
        }

        @media (max-width: 480px) {
            .reset-container {
                padding: 1.5rem;
                border-radius: 16px;
            }

            .icon-section {
                margin-bottom: 1.5rem;
            }

            .title-section {
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
        <div class="reset-container">
            <!-- Back Link -->
            <a href="/login" class="back-link">
                <i class="fas fa-arrow-left"></i>
                Volver al inicio de sesión
            </a>

            <!-- Icon Section -->
            <div class="icon-section">
                <div class="icon-container">
                    <i class="fas fa-key" style="color: white; font-size: 1.5rem;"></i>
                </div>
            </div>
            
            <!-- Title Section -->
            <div class="title-section">
                <h1 class="page-title">Recuperar contraseña</h1>
                <p class="page-subtitle">
                    Ingresa tu dirección de correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
                </p>
            </div>

            <!-- Success Alert (if exists) -->
            @if(session('status'))
            <div class="alert">
                <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>
                {{ session('status') }}
            </div>
            @endif
            
            <!-- Reset Form -->
            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Correo electrónico</label>
                    <div class="input-container">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="email" name="email" class="form-input" 
                               placeholder="ejemplo@marina.gob.mx" required 
                               value="{{ old('email') }}">
                    </div>
                    @error('email')
                    <div style="color: var(--error-color); font-size: 0.875rem; margin-top: 0.25rem;">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                
                <button type="submit" class="submit-btn">
                    <span>Enviar enlace de recuperación</span>
                </button>
            </form>
            
            <!-- Help Section -->
            <div class="help-section">
                <div class="help-title">¿Necesitas ayuda adicional?</div>
                <p class="help-text">
                    Si no tienes acceso a tu correo electrónico o sigues teniendo problemas, contacta al soporte técnico.
                </p>
                <div class="help-links">
                    <a href="#support" class="help-link">Soporte técnico</a>
                    <a href="#contact" class="help-link">Contactar administrador</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>