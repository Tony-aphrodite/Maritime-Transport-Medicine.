<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verificar Correo - Maritime Transport Medicine</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0F4C75 0%, #1B262C 50%, #0F4C75 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .verify-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            width: 100%;
            max-width: 500px;
            overflow: hidden;
            text-align: center;
        }

        .verify-header {
            background: linear-gradient(135deg, #0F4C75 0%, #3282B8 100%);
            padding: 50px 30px;
            color: white;
        }

        .verify-header .icon {
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 50%;
            margin: 0 auto 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .verify-header .icon i {
            font-size: 50px;
            color: #3282B8;
        }

        .verify-header h1 {
            font-size: 1.6rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .verify-header p {
            font-size: 0.95rem;
            opacity: 0.9;
        }

        .verify-content {
            padding: 40px 30px;
        }

        .email-display {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .email-display i {
            color: #0284c7;
            font-size: 1.2rem;
        }

        .email-display span {
            color: #0c4a6e;
            font-weight: 600;
            font-size: 1rem;
        }

        .instructions {
            color: #475569;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .instructions strong {
            color: #1e293b;
        }

        .success-message {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.9rem;
        }

        .resend-form {
            margin-bottom: 20px;
        }

        .resend-btn {
            background: linear-gradient(135deg, #0F4C75 0%, #3282B8 100%);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .resend-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(15, 76, 117, 0.3);
        }

        .resend-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .help-text {
            color: #64748b;
            font-size: 0.85rem;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .help-text a {
            color: #3282B8;
            text-decoration: none;
            font-weight: 500;
        }

        .help-text a:hover {
            text-decoration: underline;
        }

        .check-steps {
            text-align: left;
            background: #fafafa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .check-steps h4 {
            color: #1e293b;
            font-size: 0.9rem;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .check-steps ul {
            list-style: none;
            padding: 0;
        }

        .check-steps li {
            color: #475569;
            font-size: 0.85rem;
            padding: 6px 0;
            padding-left: 24px;
            position: relative;
        }

        .check-steps li::before {
            content: '•';
            position: absolute;
            left: 8px;
            color: #3282B8;
        }

        .logout-link {
            margin-top: 20px;
        }

        .logout-link a {
            color: #64748b;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .logout-link a:hover {
            color: #3282B8;
        }
    </style>
</head>
<body>
    <div class="verify-container">
        <div class="verify-header">
            <div class="icon">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <h1>Verifique su Correo Electrónico</h1>
            <p>Le hemos enviado un enlace de verificación</p>
        </div>

        <div class="verify-content">
            <div class="email-display">
                <i class="fas fa-at"></i>
                <span>{{ Auth::user()->email }}</span>
            </div>

            @if (session('message'))
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('message') }}</span>
                </div>
            @endif

            <p class="instructions">
                Hemos enviado un correo electrónico con un enlace de verificación a la dirección indicada.
                <strong>Por favor, revise su bandeja de entrada</strong> y haga clic en el enlace para verificar su cuenta.
            </p>

            <form class="resend-form" method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="resend-btn" id="resendBtn">
                    <i class="fas fa-paper-plane"></i>
                    Reenviar Correo de Verificación
                </button>
            </form>

            <div class="check-steps">
                <h4><i class="fas fa-info-circle"></i> ¿No recibió el correo?</h4>
                <ul>
                    <li>Revise su carpeta de spam o correo no deseado</li>
                    <li>Verifique que la dirección de correo sea correcta</li>
                    <li>Espere unos minutos y revise nuevamente</li>
                    <li>Si el problema persiste, haga clic en "Reenviar"</li>
                </ul>
            </div>

            @if(config('app.debug'))
            <div style="margin-top: 25px; padding: 20px; background: #fef3c7; border: 2px dashed #f59e0b; border-radius: 10px; text-align: left;">
                <h4 style="color: #92400e; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-code"></i> Modo Desarrollo
                </h4>
                <p style="color: #78350f; font-size: 0.85rem; margin-bottom: 15px;">
                    El correo está configurado en modo "log". Use este enlace para verificar su cuenta:
                </p>
                <a href="{{ \Illuminate\Support\Facades\URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), ['id' => Auth::user()->id, 'hash' => sha1(Auth::user()->email)]) }}"
                   style="display: inline-block; background: #059669; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.9rem;">
                    <i class="fas fa-check-circle"></i> Verificar Mi Correo Ahora
                </a>
                <p style="color: #92400e; font-size: 0.75rem; margin-top: 12px;">
                    <i class="fas fa-exclamation-triangle"></i> Este botón solo aparece en modo desarrollo (APP_DEBUG=true)
                </p>
            </div>
            @endif

            <div class="help-text">
                ¿Necesita ayuda? <a href="#">Contacte a soporte</a>
            </div>

            <div class="logout-link">
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Cerrar sesión y usar otro correo
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <script>
        // Disable button temporarily after click to prevent spam
        document.querySelector('.resend-form').addEventListener('submit', function() {
            const btn = document.getElementById('resendBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';

            setTimeout(function() {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane"></i> Reenviar Correo de Verificación';
            }, 30000); // Re-enable after 30 seconds
        });
    </script>
</body>
</html>
