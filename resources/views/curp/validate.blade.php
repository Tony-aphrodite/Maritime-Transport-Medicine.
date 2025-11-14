<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validación de CURP - MARINA</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        .validation-container {
            background: var(--background-white);
            border-radius: 20px;
            box-shadow: var(--shadow-xl);
            padding: 2.5rem;
            width: 100%;
            max-width: 500px;
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
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: 20px;
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
            font-size: 1.875rem;
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
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid var(--border-light);
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.2s ease;
            background-color: var(--background-white);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-family: 'Courier New', monospace;
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
            text-transform: none;
            font-family: 'Inter', sans-serif;
            letter-spacing: normal;
        }

        .form-input.valid {
            border-color: var(--success-color);
            background-color: #f0fdf4;
        }

        .form-input.invalid {
            border-color: var(--error-color);
            background-color: #fef2f2;
        }

        .validation-status {
            margin-top: 0.5rem;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-height: 1.25rem;
        }

        .validation-status.success {
            color: var(--success-color);
        }

        .validation-status.error {
            color: var(--error-color);
        }

        .validation-status.info {
            color: var(--text-secondary);
        }

        .submit-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
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

        .submit-btn:hover:not(:disabled)::before {
            left: 100%;
        }

        .submit-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .loading-spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .result-card {
            display: none;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border: 1px solid;
        }

        .result-card.success {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border-color: var(--success-color);
            color: #065f46;
        }

        .result-card.error {
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
            border-color: var(--error-color);
            color: #991b1b;
        }

        .result-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .result-content {
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .curp-details {
            background: rgba(255, 255, 255, 0.7);
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 0.25rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 500;
            color: var(--text-secondary);
        }

        .detail-value {
            font-weight: 600;
            font-family: 'Courier New', monospace;
        }

        .info-section {
            background: var(--background-light);
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid var(--border-light);
        }

        .info-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-text {
            font-size: 0.85rem;
            color: var(--text-secondary);
            line-height: 1.5;
            margin-bottom: 0.75rem;
        }

        .info-list {
            list-style: none;
            padding: 0;
        }

        .info-list li {
            font-size: 0.85rem;
            color: var(--text-secondary);
            padding: 0.25rem 0;
            position: relative;
            padding-left: 1.5rem;
        }

        .info-list li::before {
            content: '•';
            color: var(--primary-color);
            position: absolute;
            left: 0.5rem;
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

            .validation-container {
                padding: 2rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .icon-container {
                width: 60px;
                height: 60px;
            }
        }

        @media (max-width: 480px) {
            .validation-container {
                padding: 1.5rem;
                border-radius: 16px;
            }

            .detail-item {
                flex-direction: column;
                gap: 0.25rem;
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
                <svg viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg" style="width: 32px; height: 32px;">
                    <path d="M60 10 L85 20 L85 50 Q85 75 60 100 Q35 75 35 50 L35 20 Z" 
                          fill="#0F4C75" stroke="#3282B8" stroke-width="2"/>
                    <path d="M60 15 L80 23 L80 48 Q80 70 60 90 Q40 70 40 48 L40 23 Z" 
                          fill="white" opacity="0.95"/>
                    <rect x="57" y="35" width="6" height="20" fill="#0F4C75"/>
                    <rect x="50" y="42" width="20" height="6" fill="#0F4C75"/>
                    <g stroke="#3282B8" stroke-width="1.5" fill="none" opacity="0.8">
                        <path d="M45 30 L50 30 L52 32 L55 32"/>
                        <path d="M65 32 L68 32 L70 30 L75 30"/>
                        <path d="M45 60 L48 60 L50 58 L53 58"/>
                        <path d="M67 58 L70 58 L72 60 L75 60"/>
                        <circle cx="47" cy="30" r="1.5" fill="#3282B8"/>
                        <circle cx="73" cy="30" r="1.5" fill="#3282B8"/>
                        <circle cx="47" cy="60" r="1.5" fill="#3282B8"/>
                        <circle cx="73" cy="60" r="1.5" fill="#3282B8"/>
                    </g>
                    <path d="M40 70 Q50 65 60 70 T80 70" 
                          stroke="#BBE1FA" stroke-width="2" fill="none"/>
                    <path d="M42 75 Q52 70 62 75 T82 75" 
                          stroke="#BBE1FA" stroke-width="1.5" fill="none" opacity="0.6"/>
                    <circle cx="60" cy="25" r="2" fill="#3282B8"/>
                    <rect x="58" y="78" width="4" height="4" fill="#3282B8" rx="1"/>
                </svg>
            </div>
            <div class="title">MARINA - Secretaría de Marina</div>
        </div>
        <nav class="nav-links">
            <a href="/registro">Registro</a>
            <a href="/login">Iniciar Sesión</a>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="validation-container">
            <!-- Back Link -->
            <a href="/registro" class="back-link">
                <i class="fas fa-arrow-left"></i>
                Volver al registro
            </a>

            <!-- Icon Section -->
            <div class="icon-section">
                <div class="icon-container">
                    <i class="fas fa-id-card" style="color: white; font-size: 2rem;"></i>
                </div>
            </div>
            
            <!-- Title Section -->
            <div class="title-section">
                <h1 class="page-title">Validación de CURP</h1>
                <p class="page-subtitle">
                    Ingrese su CURP para validar los datos contra la base de datos oficial de RENAPO
                </p>
            </div>

            <!-- Result Card -->
            <div id="resultCard" class="result-card">
                <div class="result-header">
                    <i id="resultIcon"></i>
                    <span id="resultTitle"></span>
                </div>
                <div class="result-content">
                    <div id="resultMessage"></div>
                    <div id="curpDetails" class="curp-details" style="display: none;"></div>
                </div>
            </div>
            
            <!-- Validation Form -->
            <form id="curpValidationForm">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="curp">CURP (Clave Única de Registro de Población)</label>
                    <div class="input-container">
                        <i class="fas fa-id-card input-icon"></i>
                        <input type="text" 
                               id="curp" 
                               name="curp" 
                               class="form-input" 
                               placeholder="Ej: PEGJ850415HDFRRN05" 
                               maxlength="18"
                               required>
                    </div>
                    <div id="validationStatus" class="validation-status info">
                        <i class="fas fa-info-circle"></i>
                        <span>CURP debe contener exactamente 18 caracteres alfanuméricos</span>
                    </div>
                </div>
                
                <button type="submit" id="submitBtn" class="submit-btn" disabled>
                    <span id="btnText">Validar CURP</span>
                    <div id="loadingSpinner" class="loading-spinner" style="display: none;"></div>
                </button>
            </form>
            
            <!-- Info Section -->
            <div class="info-section">
                <div class="info-title">
                    <i class="fas fa-info-circle"></i>
                    Información sobre CURP
                </div>
                <p class="info-text">
                    El CURP es obligatorio para todos los procedimientos oficiales en México desde febrero 2026.
                    Esta validación consulta directamente la base de datos oficial de RENAPO.
                </p>
                <ul class="info-list">
                    <li>Documento oficial de identificación nacional</li>
                    <li>Contiene datos biométricos integrados</li>
                    <li>Válido para cualquier trámite gubernamental</li>
                    <li>Proceso de validación en tiempo real</li>
                </ul>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('curpValidationForm');
            const curpInput = document.getElementById('curp');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const validationStatus = document.getElementById('validationStatus');
            const resultCard = document.getElementById('resultCard');
            
            // CURP format validation regex
            const curpRegex = /^[A-Z]{1}[AEIOUX]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01])[HM]{1}[A-Z]{2}[BCDFGHJKLMNPQRSTVWXYZ]{3}[0-9A-Z]{1}[0-9]{1}$/;
            
            // Real-time CURP validation
            curpInput.addEventListener('input', function() {
                let value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
                this.value = value;
                
                if (value.length === 0) {
                    resetValidation();
                    return;
                }
                
                if (value.length !== 18) {
                    showValidationMessage('error', 'CURP debe tener exactamente 18 caracteres', 'fas fa-exclamation-circle');
                    curpInput.classList.remove('valid');
                    curpInput.classList.add('invalid');
                    submitBtn.disabled = true;
                    return;
                }
                
                if (!curpRegex.test(value)) {
                    showValidationMessage('error', 'Formato de CURP inválido', 'fas fa-times-circle');
                    curpInput.classList.remove('valid');
                    curpInput.classList.add('invalid');
                    submitBtn.disabled = true;
                    return;
                }
                
                showValidationMessage('success', 'Formato de CURP válido', 'fas fa-check-circle');
                curpInput.classList.remove('invalid');
                curpInput.classList.add('valid');
                submitBtn.disabled = false;
            });
            
            // Form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                validateCurp();
            });
            
            function resetValidation() {
                curpInput.classList.remove('valid', 'invalid');
                showValidationMessage('info', 'CURP debe contener exactamente 18 caracteres alfanuméricos', 'fas fa-info-circle');
                submitBtn.disabled = true;
                hideResult();
            }
            
            function showValidationMessage(type, message, icon) {
                validationStatus.className = `validation-status ${type}`;
                validationStatus.innerHTML = `<i class="${icon}"></i><span>${message}</span>`;
            }
            
            function showLoading() {
                submitBtn.disabled = true;
                btnText.style.display = 'none';
                loadingSpinner.style.display = 'block';
            }
            
            function hideLoading() {
                submitBtn.disabled = false;
                btnText.style.display = 'block';
                loadingSpinner.style.display = 'none';
            }
            
            function showResult(success, title, message, data = null) {
                resultCard.className = `result-card ${success ? 'success' : 'error'}`;
                document.getElementById('resultIcon').className = success ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle';
                document.getElementById('resultTitle').textContent = title;
                document.getElementById('resultMessage').textContent = message;
                
                const detailsDiv = document.getElementById('curpDetails');
                if (success && data && data.details) {
                    let detailsHtml = '';
                    if (data.details.nombres) detailsHtml += `<div class="detail-item"><span class="detail-label">Nombre:</span><span class="detail-value">${data.details.nombres}</span></div>`;
                    if (data.details.apellidos) detailsHtml += `<div class="detail-item"><span class="detail-label">Apellidos:</span><span class="detail-value">${data.details.apellidos}</span></div>`;
                    if (data.details.fechaNacimiento) detailsHtml += `<div class="detail-item"><span class="detail-label">Fecha de Nacimiento:</span><span class="detail-value">${data.details.fechaNacimiento}</span></div>`;
                    if (data.details.sexo) detailsHtml += `<div class="detail-item"><span class="detail-label">Sexo:</span><span class="detail-value">${data.details.sexo}</span></div>`;
                    if (data.details.entidadNacimiento) detailsHtml += `<div class="detail-item"><span class="detail-label">Estado:</span><span class="detail-value">${data.details.entidadNacimiento}</span></div>`;
                    
                    detailsDiv.innerHTML = detailsHtml;
                    detailsDiv.style.display = detailsHtml ? 'block' : 'none';
                } else {
                    detailsDiv.style.display = 'none';
                }
                
                resultCard.style.display = 'block';
                resultCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
            
            function hideResult() {
                resultCard.style.display = 'none';
            }
            
            async function validateCurp() {
                const curp = curpInput.value.trim().toUpperCase();
                
                if (!curp || curp.length !== 18) {
                    showResult(false, 'Error de Validación', 'Por favor ingrese un CURP válido de 18 caracteres');
                    return;
                }
                
                showLoading();
                hideResult();
                
                try {
                    const response = await fetch('/curp/validate', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ curp: curp })
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok && data.success) {
                        showResult(true, 'CURP Válido', data.message, data.data);
                    } else {
                        showResult(false, 'CURP No Válido', data.message || 'Error al validar el CURP');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showResult(false, 'Error de Conexión', 'Error al conectar con el servidor. Verifique su conexión a internet.');
                } finally {
                    hideLoading();
                }
            }
        });
    </script>
</body>
</html>