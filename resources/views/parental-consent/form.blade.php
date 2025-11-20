<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consentimiento Parental - Sistema Digital de Certificación Médica</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 1rem;
        }
        .container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #0F4C75, #3282B8);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .header h1 { font-size: 1.8rem; margin-bottom: 0.5rem; }
        .header p { opacity: 0.9; }
        .content { padding: 2rem; }
        .minor-info {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 1rem;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 0.5rem;
        }
        .btn-success {
            background: #10b981;
            color: white;
        }
        .btn-danger {
            background: #ef4444;
            color: white;
        }
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
        }
        .alert-warning {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            color: #92400e;
        }
        .legal-text {
            font-size: 0.875rem;
            line-height: 1.5;
            color: #6b7280;
            margin: 1rem 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <i class="fas fa-user-shield" style="font-size: 3rem; margin-bottom: 1rem;"></i>
            <h1>Consentimiento Parental Requerido</h1>
            <p>Sistema Digital de Certificación Médica Marítima</p>
        </div>

        <div class="content">
            <div class="minor-info">
                <h3 style="color: #374151; margin-bottom: 1rem;">
                    <i class="fas fa-info-circle"></i> Información del Menor
                </h3>
                <p><strong>Nombre:</strong> {{ $consent->minor_full_name }}</p>
                <p><strong>Edad:</strong> {{ $consent->minor_age }} años</p>
                <p><strong>Correo:</strong> {{ $consent->minor_email }}</p>
                <p><strong>Fecha de Nacimiento:</strong> {{ $consent->minor_birth_date->format('d/m/Y') }}</p>
            </div>

            <div class="alert alert-warning">
                <strong><i class="fas fa-exclamation-triangle"></i> Consentimiento Requerido</strong><br>
                Su hijo/hija menor de edad desea registrarse en nuestro Sistema Digital de Certificación Médica Marítima. 
                Como padre, madre o tutor legal, su consentimiento es requerido por ley para procesar este registro.
            </div>

            <form method="POST" action="{{ route('parental.consent.process', $consent->consent_token) }}">
                @csrf
                
                <div class="form-group">
                    <label for="digital_signature">
                        <i class="fas fa-signature"></i> Firma Digital (Escriba su nombre completo)
                    </label>
                    <input type="text" 
                           id="digital_signature" 
                           name="digital_signature" 
                           class="form-control" 
                           placeholder="Escriba su nombre completo como firma digital"
                           required>
                    <small class="legal-text">Esta firma digital constituye su consentimiento legal válido.</small>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="terms_accepted" value="1" required style="margin-right: 0.5rem;">
                        <strong>Acepto los términos y condiciones</strong>
                    </label>
                    <div class="legal-text">
                        Al marcar esta casilla, confirmo que:
                        <ul style="margin-left: 1.5rem; margin-top: 0.5rem;">
                            <li>Soy el padre, madre o tutor legal del menor mencionado</li>
                            <li>Doy mi consentimiento para que el menor se registre en el sistema</li>
                            <li>Entiendo que el menor utilizará servicios de certificación médica marítima</li>
                            <li>Acepto los términos de privacidad y uso de datos personales</li>
                        </ul>
                    </div>
                </div>

                <div style="text-align: center; margin-top: 2rem;">
                    <button type="submit" name="decision" value="approve" class="btn btn-success">
                        <i class="fas fa-check"></i> Aprobar Registro
                    </button>
                    
                    <button type="submit" name="decision" value="deny" class="btn btn-danger">
                        <i class="fas fa-times"></i> Denegar Registro
                    </button>
                </div>

                <div class="legal-text" style="text-align: center; margin-top: 1.5rem;">
                    <p><strong>Nota:</strong> Esta solicitud expira el {{ $consent->expires_at->format('d/m/Y H:i') }}</p>
                    <p>Si tiene preguntas, contacte al administrador del sistema.</p>
                </div>
            </form>
        </div>
    </div>

    @if(session('error'))
    <script>
        alert('{{ session('error') }}');
    </script>
    @endif
</body>
</html>