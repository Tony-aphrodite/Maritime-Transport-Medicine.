<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayuda - Advertencia de Seguridad</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background: #f8fafc;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .warning-box {
            background: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 10px;
            padding: 25px;
            margin: 25px 0;
            color: #92400e;
        }
        .solution-box {
            background: #dcfce7;
            border: 2px solid #16a34a;
            border-radius: 10px;
            padding: 25px;
            margin: 25px 0;
            color: #15803d;
        }
        .step {
            background: #f3f4f6;
            margin: 15px 0;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #3b82f6;
        }
        .step-number {
            background: #3b82f6;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-right: 15px;
        }
        .browser-specific {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .credentials {
            background: #f0f9ff;
            border: 2px solid #3b82f6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            font-family: monospace;
        }
        .link-button {
            display: inline-block;
            background: #3b82f6;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 10px 10px 10px 0;
        }
        .success-button {
            background: #16a34a;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-shield-alt"></i> Solución: Advertencia de Seguridad del Navegador</h1>

        <div class="warning-box">
            <h3><i class="fas fa-exclamation-triangle"></i> El Problema</h3>
            <p><strong>Mensaje que aparece:</strong> "The information you're about to submit is not secure"</p>
            <p><strong>Causa:</strong> Estás usando HTTP (localhost:8000) en lugar de HTTPS para desarrollo local.</p>
        </div>

        <div class="solution-box">
            <h3><i class="fas fa-check-circle"></i> ¡Esto es NORMAL y SEGURO!</h3>
            <p><strong>Para desarrollo local en localhost:8000, es completamente seguro ignorar esta advertencia.</strong></p>
        </div>

        <h3><i class="fas fa-mouse-pointer"></i> Cómo Proceder (Según tu Navegador):</h3>

        <div class="browser-specific">
            <h4><i class="fab fa-chrome"></i> Chrome / Edge</h4>
            <div class="step">
                <span class="step-number">1</span>
                Cuando aparezca la advertencia, busca el botón <strong>"Send anyway"</strong> o <strong>"Submit anyway"</strong>
            </div>
            <div class="step">
                <span class="step-number">2</span>
                Haz clic en ese botón para continuar con el login
            </div>
        </div>

        <div class="browser-specific">
            <h4><i class="fab fa-firefox"></i> Firefox</h4>
            <div class="step">
                <span class="step-number">1</span>
                Busca el botón <strong>"Enviar de todas formas"</strong> o <strong>"Continue"</strong>
            </div>
            <div class="step">
                <span class="step-number">2</span>
                Confirma para continuar con el envío del formulario
            </div>
        </div>

        <div class="browser-specific">
            <h4><i class="fab fa-safari"></i> Safari</h4>
            <div class="step">
                <span class="step-number">1</span>
                Busca <strong>"Continue"</strong> o <strong>"Submit"</strong>
            </div>
            <div class="step">
                <span class="step-number">2</span>
                Haz clic para proceder con el login
            </div>
        </div>

        <h3><i class="fas fa-key"></i> Recordatorio de Credenciales:</h3>
        <div class="credentials">
            <div><strong>Email:</strong> AdminJuan@gmail.com</div>
            <div><strong>Password:</strong> johnson@suceess!</div>
        </div>

        <h3><i class="fas fa-list-ol"></i> Proceso Completo de Login:</h3>

        <div class="step">
            <span class="step-number">1</span>
            Ir a la página de login: <code>http://localhost:8000/login</code>
        </div>

        <div class="step">
            <span class="step-number">2</span>
            Ingresar las credenciales de administrador
        </div>

        <div class="step">
            <span class="step-number">3</span>
            Hacer clic en "Iniciar Sesión"
        </div>

        <div class="step">
            <span class="step-number">4</span>
            <strong>Cuando aparezca la advertencia de seguridad:</strong> Hacer clic en "Send anyway" / "Submit anyway"
        </div>

        <div class="step">
            <span class="step-number">5</span>
            ¡Serás redirigido al panel de administración!
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="/login" class="link-button success-button">
                <i class="fas fa-sign-in-alt"></i> Ir a Login Ahora
            </a>
            
            <a href="/admin/admin-status" class="link-button" target="_blank">
                <i class="fas fa-info-circle"></i> Ver Estado de Sesión
            </a>
        </div>

        <div style="margin-top: 40px; padding: 20px; background: #f0f9ff; border-radius: 8px;">
            <h4><i class="fas fa-lightbulb"></i> ¿Por qué pasa esto?</h4>
            <p>Los navegadores modernos muestran esta advertencia cuando envías formularios a través de HTTP (no HTTPS). 
            Para desarrollo local en <code>localhost:8000</code>, esto es completamente normal y seguro.</p>
            <p><strong>En producción, siempre deberías usar HTTPS, pero para desarrollo local con localhost es seguro continuar.</strong></p>
        </div>
    </div>
</body>
</html>