<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consentimiento Aprobado - Sistema Digital de Certificación Médica</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            min-height: 100vh;
            padding: 2rem 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            max-width: 600px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            padding: 3rem 2rem;
            text-align: center;
        }
        .success-icon {
            font-size: 4rem;
            color: #10b981;
            margin-bottom: 2rem;
        }
        h1 {
            font-size: 2rem;
            color: #065f46;
            margin-bottom: 1rem;
        }
        .message {
            font-size: 1.1rem;
            color: #374151;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .info-box {
            background: #f0fdf4;
            border: 1px solid #22c55e;
            border-radius: 8px;
            padding: 1.5rem;
            margin: 2rem 0;
            text-align: left;
        }
        .info-box h3 {
            color: #065f46;
            margin-bottom: 1rem;
        }
        .info-box p {
            margin-bottom: 0.5rem;
            color: #166534;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        
        <h1>¡Consentimiento Aprobado!</h1>
        
        <div class="message">
            Gracias por aprobar el registro de <strong>{{ $consent->minor_full_name }}</strong> 
            en nuestro Sistema Digital de Certificación Médica Marítima.
        </div>

        <div class="info-box">
            <h3><i class="fas fa-info-circle"></i> Próximos Pasos</h3>
            <p>• El menor ha sido notificado de su aprobación</p>
            <p>• Puede continuar con el proceso de registro</p>
            <p>• Recibirá actualizaciones sobre el progreso del registro</p>
            <p>• La cuenta estará sujeta a verificaciones adicionales de seguridad</p>
        </div>

        <div style="margin-top: 2rem; font-size: 0.875rem; color: #6b7280;">
            <p>Fecha de Aprobación: {{ $consent->consent_given_at->format('d/m/Y H:i') }}</p>
            <p>Consentimiento ID: {{ $consent->id }}</p>
        </div>
    </div>
</body>
</html>