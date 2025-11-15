<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Administrativo - MARINA</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 450px;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #1e40af, #3b82f6, #06b6d4);
        }

        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
        }

        .logo i {
            font-size: 2.5rem;
        }

        h1 {
            color: #1f2937;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #6b7280;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 24px;
            position: relative;
        }

        label {
            display: block;
            color: #374151;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .input-wrapper {
            position: relative;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 14px 48px 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #3b82f6;
            background: white;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .input-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 1.1rem;
        }

        .login-btn {
            width: 100%;
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
        }

        .login-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid #ffffff40;
            border-top: 2px solid #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            display: none;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .alert-error {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #dcfce7;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .back-link {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        .back-link a {
            color: #6b7280;
            text-decoration: none;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: color 0.3s ease;
        }

        .back-link a:hover {
            color: #3b82f6;
        }

        .security-note {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 8px;
            padding: 12px;
            margin-top: 20px;
            font-size: 0.85rem;
            color: #0369a1;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
                margin: 10px;
            }
            
            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-section">
            <div class="logo">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h1>Acceso Administrativo</h1>
            <p class="subtitle">Sistema de Gestión MARINA</p>
        </div>

        @if (session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i>
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}" id="loginForm">
            @csrf
            
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <div class="input-wrapper">
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        placeholder="admin@marina.gob.mx"
                        required
                        autocomplete="username"
                    >
                    <div class="input-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>
                @error('email')
                    <div style="color: #dc2626; font-size: 0.85rem; margin-top: 5px;">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <div class="input-wrapper">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="••••••••••••"
                        required
                        autocomplete="current-password"
                    >
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                </div>
                @error('password')
                    <div style="color: #dc2626; font-size: 0.85rem; margin-top: 5px;">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="login-btn" id="loginBtn">
                <span class="btn-text">
                    <i class="fas fa-sign-in-alt"></i>
                    Iniciar Sesión
                </span>
                <div class="spinner"></div>
            </button>
        </form>

        <div class="security-note">
            <i class="fas fa-info-circle" style="margin-right: 8px;"></i>
            Acceso restringido para personal autorizado únicamente
        </div>

        <div class="back-link">
            <a href="{{ url('/') }}">
                <i class="fas fa-arrow-left"></i>
                Volver al sitio principal
            </a>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            const spinner = btn.querySelector('.spinner');
            const text = btn.querySelector('.btn-text');
            
            btn.disabled = true;
            spinner.style.display = 'block';
            text.style.display = 'none';
        });

        // Auto-focus email field
        document.getElementById('email').focus();
    </script>
</body>
</html>