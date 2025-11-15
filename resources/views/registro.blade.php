<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario - MARINA</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: #2d3748;
            line-height: 1.6;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Custom Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.05);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(139, 21, 56, 0.2);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(139, 21, 56, 0.4);
        }

        /* Firefox Scrollbar */
        html {
            scrollbar-width: thin;
            scrollbar-color: rgba(139, 21, 56, 0.2) rgba(0,0,0,0.05);
            scroll-behavior: smooth;
        }

        /* Optional: Hide scrollbar completely for cleaner look */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Registration Methods Section */
        .registration-methods {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(15, 76, 117, 0.08);
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(15, 76, 117, 0.1);
        }

        .methods-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .methods-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0F4C75;
            margin-bottom: 0.5rem;
        }

        .methods-subtitle {
            color: #64748b;
            font-size: 0.95rem;
            margin: 0;
        }

        .method-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .method-option {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: left;
            text-decoration: none;
        }

        .method-option:hover {
            border-color: #0F4C75;
            background: #f1f8ff;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(15, 76, 117, 0.15);
        }

        .method-option.selected {
            border-color: #0F4C75;
            background: linear-gradient(135deg, #f1f8ff, #e6f3ff);
            box-shadow: 0 8px 20px rgba(15, 76, 117, 0.2);
        }

        .method-icon {
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            margin-right: 1rem;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .traditional-method .method-icon {
            background: linear-gradient(135deg, #3B82F6, #1D4ED8);
            color: white;
        }

        .curp-method .method-icon {
            background: linear-gradient(135deg, #0F4C75, #3282B8);
            color: white;
        }

        .method-content {
            flex: 1;
        }

        .method-title {
            display: block;
            font-weight: 600;
            font-size: 1.1rem;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .method-description {
            display: block;
            font-size: 0.9rem;
            color: #64748b;
            line-height: 1.4;
        }

        .method-status {
            color: #64748b;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .method-option:hover .method-status {
            color: #0F4C75;
            transform: translateX(4px);
        }

        .method-option.selected .method-status {
            color: #10B981;
        }

        /* Responsive for method selection */
        @media (max-width: 768px) {
            .method-options {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .method-option {
                padding: 1.25rem;
            }
            
            .method-icon {
                width: 48px;
                height: 48px;
                font-size: 1.25rem;
            }
            
            .methods-title {
                font-size: 1.3rem;
            }
        }

        /* Header */
        .main-header {
            background: linear-gradient(135deg, #0F4C75 0%, #3282B8 100%);
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 8px 32px rgba(15, 76, 117, 0.3);
            position: relative;
            overflow: hidden;
        }

        .main-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="0.5" fill="%23ffffff" opacity="0.03"/><circle cx="75" cy="75" r="0.5" fill="%23ffffff" opacity="0.03"/><circle cx="50" cy="10" r="0.3" fill="%23ffffff" opacity="0.02"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            pointer-events: none;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 24px;
            position: relative;
            z-index: 1;
        }

        .government-seal {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .government-seal svg {
            width: 80%;
            height: 80%;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));
        }

        .header-title {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .header-nav {
            display: flex;
            gap: 8px;
            position: relative;
            z-index: 1;
        }

        .header-nav a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 12px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .header-nav a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        /* Main Content */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1.5rem 2rem;
        }

        .page-header {
            text-align: center;
            margin-bottom: 1.5rem;
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.3);
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #0F4C75, #3282B8, #0F4C75);
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #0F4C75, #3282B8);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            font-size: 1rem;
            color: #64748b;
            font-weight: 500;
            position: relative;
        }

        .page-subtitle::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 2px;
            background: linear-gradient(90deg, #0F4C75, #3282B8);
            border-radius: 2px;
        }

        /* Form Sections */
        .form-section {
            margin-bottom: 1.5rem;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            border: 1px solid rgba(255,255,255,0.3);
            transition: all 0.3s ease;
        }

        .form-section:hover {
            box-shadow: 0 12px 35px rgba(0,0,0,0.12);
            transform: translateY(-2px);
        }

        .section-header {
            background: linear-gradient(135deg, #0F4C75 0%, #3282B8 100%);
            color: white;
            padding: 1rem 1.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
        }

        .section-header i {
            font-size: 1.1rem;
        }

        .section-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #D4AF37, transparent, #D4AF37);
        }

        .section-content {
            padding: 1.5rem;
            background: #fafbfc;
        }

        /* Modern Field Layout */
        .field-grid {
            display: grid;
            gap: 1.2rem;
        }

        .field-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .field-label {
            font-weight: 600;
            color: #374151;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .field-label i {
            color: #0F4C75;
            font-size: 1rem;
        }

        .required {
            color: #ef4444;
            font-weight: 700;
        }

        /* Form Controls */
        .form-control, .form-select {
            width: 100%;
            padding: 12px 14px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s ease;
            background: white;
            color: #374151;
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: #0F4C75;
            box-shadow: 0 0 0 4px rgba(15, 76, 117, 0.1);
            transform: translateY(-2px);
        }

        .form-control:hover, .form-select:hover {
            border-color: #cbd5e1;
        }

        .form-control::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        /* Radio Buttons */
        .radio-group {
            display: flex;
            gap: 16px;
            align-items: center;
            flex-wrap: wrap;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .radio-option:hover {
            border-color: #0F4C75;
            background: #f0f8ff;
        }

        .radio-option input[type="radio"] {
            width: 20px;
            height: 20px;
            accent-color: #0F4C75;
            cursor: pointer;
        }

        .radio-option input[type="radio"]:checked + label,
        .radio-option:has(input[type="radio"]:checked) {
            border-color: #0F4C75;
            background: linear-gradient(135deg, #f0f8ff, #e6f3ff);
            color: #0F4C75;
        }

        .radio-option label {
            cursor: pointer;
            font-weight: 500;
            margin: 0;
        }

        /* Input with Button */
        .input-with-button {
            display: flex;
            gap: 12px;
            align-items: stretch;
        }

        .input-with-button .form-control {
            flex: 1;
        }

        .search-btn {
            padding: 12px 18px;
            background: linear-gradient(135deg, #0F4C75, #3282B8);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.9rem;
        }

        .search-btn:hover {
            background: linear-gradient(135deg, #0A3A5C, #0F4C75);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(15, 76, 117, 0.3);
        }

        .search-btn i {
            font-size: 1rem;
        }

        /* Grid Layouts */
        .two-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.2rem;
        }

        .three-columns {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.2rem;
        }

        /* Action Buttons */
        .form-actions {
            text-align: center;
            margin-top: 2rem;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            border: 1px solid rgba(255,255,255,0.3);
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin: 0 6px;
            min-width: 140px;
            justify-content: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0F4C75, #3282B8);
            color: white;
            box-shadow: 0 8px 25px rgba(15, 76, 117, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0A3A5C, #0F4C75);
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(15, 76, 117, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #64748b, #475569);
            color: white;
            box-shadow: 0 8px 25px rgba(100, 116, 139, 0.3);
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #475569, #334155);
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(100, 116, 139, 0.4);
        }

        .login-redirect {
            text-align: center;
            margin-top: 1.5rem;
            padding: 1.5rem;
            background: rgba(255,255,255,0.7);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.3);
        }

        .login-redirect a {
            color: #0F4C75;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-redirect a:hover {
            color: #3282B8;
            text-decoration: underline;
        }

        /* Progress Bar */
        .progress-bar {
            width: 100%;
            height: 4px;
            background: #e2e8f0;
            border-radius: 2px;
            margin-bottom: 1rem;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #0F4C75, #3282B8);
            width: 33%;
            border-radius: 3px;
            animation: progressAnimation 2s ease-in-out;
        }

        @keyframes progressAnimation {
            from { width: 0%; }
            to { width: 33%; }
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .three-columns {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .main-header {
                flex-direction: column;
                gap: 1.5rem;
                text-align: center;
                padding: 2rem 1rem;
            }

            .header-nav {
                gap: 12px;
                flex-wrap: wrap;
                justify-content: center;
            }

            .container {
                padding: 2rem 1rem;
            }

            .page-header {
                padding: 2rem;
                margin-bottom: 2rem;
            }

            .page-title {
                font-size: 2rem;
            }

            .two-columns, .three-columns {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .input-with-button {
                flex-direction: column;
                align-items: stretch;
            }

            .btn {
                margin: 8px 0;
                width: 100%;
            }

            .section-content {
                padding: 1.5rem;
            }

            .radio-group {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }

            .radio-option {
                justify-content: center;
            }
        }

        /* Loading animation */
        .form-loading {
            display: none;
            text-align: center;
            padding: 2rem;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #e2e8f0;
            border-top: 4px solid #0F4C75;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Enhanced visual effects */
        .section-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, #D4AF37, transparent);
        }

        .form-section {
            position: relative;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="header-left">
            <div class="government-seal">
                <svg viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg">
                    <!-- Shield Background -->
                    <path d="M60 10 L85 20 L85 50 Q85 75 60 100 Q35 75 35 50 L35 20 Z" 
                          fill="#0F4C75" stroke="#3282B8" stroke-width="2"/>
                    
                    <!-- Inner Shield -->
                    <path d="M60 15 L80 23 L80 48 Q80 70 60 90 Q40 70 40 48 L40 23 Z" 
                          fill="white" opacity="0.95"/>
                    
                    <!-- Medical Cross -->
                    <rect x="57" y="35" width="6" height="20" fill="#0F4C75"/>
                    <rect x="50" y="42" width="20" height="6" fill="#0F4C75"/>
                    
                    <!-- Digital Circuit Lines -->
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
                    
                    <!-- Maritime Wave -->
                    <path d="M40 70 Q50 65 60 70 T80 70" 
                          stroke="#BBE1FA" stroke-width="2" fill="none"/>
                    <path d="M42 75 Q52 70 62 75 T82 75" 
                          stroke="#BBE1FA" stroke-width="1.5" fill="none" opacity="0.6"/>
                    
                    <!-- Blue Accents -->
                    <circle cx="60" cy="25" r="2" fill="#3282B8"/>
                    <rect x="58" y="78" width="4" height="4" fill="#3282B8" rx="1"/>
                </svg>
            </div>
            <div class="header-title">MARINA - Secretaría de Marina</div>
        </div>
        <nav class="header-nav">
            <a href="#tramites"><i class="fas fa-file-alt"></i> Trámites</a>
            <a href="#gobierno"><i class="fas fa-building"></i> Gobierno</a>
        </nav>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Registro de Usuario</h1>
            <p class="page-subtitle">Sistema Digital de Certificación Médica</p>
            
            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>
            <p style="color: #64748b; font-size: 0.85rem; margin-top: 0.5rem;">
                <i class="fas fa-info-circle"></i> Complete todos los campos requeridos para continuar
            </p>
        </div>

        <!-- Registration Method Selection -->
        <div class="registration-methods" id="registrationMethods">
            <div class="methods-header">
                <h2 class="methods-title">Seleccione su método de registro</h2>
                <p class="methods-subtitle">Elija cómo desea verificar su identidad</p>
            </div>
            
            <div class="method-options">
                <button type="button" class="method-option traditional-method" onclick="selectRegistrationMethod('traditional')">
                    <div class="method-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="method-content">
                        <span class="method-title">Registro Tradicional</span>
                        <span class="method-description">Complete manualmente todos los campos del formulario</span>
                    </div>
                    <div class="method-status">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </button>
                
                <button type="button" class="method-option curp-method" onclick="selectRegistrationMethod('curp')">
                    <div class="method-icon">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div class="method-content">
                        <span class="method-title">Registro con CURP</span>
                        <span class="method-description">Verificar identidad y auto-completar datos con CURP oficial</span>
                    </div>
                    <div class="method-status">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </button>
            </div>
        </div>

        <form action="#" method="POST" id="registryForm" style="display: none;">
            <!-- Section 1 - General Information -->
            <div class="form-section">
                <div class="section-header">
                    <i class="fas fa-user-circle"></i>
                    Sección 1 – Información General
                </div>
                <div class="section-content">
                    <div class="field-grid">
                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-question-circle"></i>
                                ¿Cuenta con Expediente Médico? <span class="required">*</span>
                            </label>
                            <div class="radio-group">
                                <div class="radio-option">
                                    <input type="radio" id="expediente_si" name="tiene_expediente" value="si">
                                    <label for="expediente_si">Sí</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" id="expediente_no" name="tiene_expediente" value="no">
                                    <label for="expediente_no">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-folder-open"></i>
                                Expediente Médico
                            </label>
                            <div class="input-with-button">
                                <input type="text" class="form-control" name="expediente_medico" placeholder="Número de expediente médico">
                                <button type="button" class="search-btn">
                                    <i class="fas fa-search"></i>
                                    Buscar Expediente Méd.
                                </button>
                            </div>
                        </div>

                        <div class="two-columns">
                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-flag"></i>
                                    Nacionalidad <span class="required">*</span>
                                </label>
                                <select class="form-select" name="nacionalidad" required>
                                    <option value="">Seleccione...</option>
                                    <option value="mexicana">Mexicana</option>
                                    <option value="estadounidense">Estadounidense</option>
                                    <option value="canadiense">Canadiense</option>
                                    <option value="otra">Otra</option>
                                </select>
                            </div>

                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-id-card"></i>
                                    CURP <span class="required">*</span>
                                </label>
                                <div class="input-with-button">
                                    <input type="text" 
                                           class="form-control" 
                                           id="curpInput" 
                                           name="curp" 
                                           placeholder="CURP (18 caracteres)" 
                                           maxlength="18"
                                           style="text-transform: uppercase; font-family: 'Courier New', monospace; letter-spacing: 0.5px;"
                                           required>
                                    <button type="button" onclick="validateCurpFromRegistry()" class="search-btn" style="border: none; cursor: pointer;">
                                        <i class="fas fa-check-circle"></i>
                                        Validar CURP
                                    </button>
                                </div>
                                <div id="curpValidationMessage" style="margin-top: 0.5rem; font-size: 0.875rem; display: none;"></div>
                            </div>
                        </div>

                        <div class="two-columns">
                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-receipt"></i>
                                    RFC
                                </label>
                                <input type="text" class="form-control" name="rfc" placeholder="RFC (13 caracteres)" maxlength="13">
                            </div>

                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-envelope"></i>
                                    Correo Electrónico <span class="required">*</span>
                                </label>
                                <input type="email" class="form-control" name="email" placeholder="correo@ejemplo.com" required>
                            </div>
                        </div>

                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-envelope-open"></i>
                                Correo Electrónico Alternativo
                            </label>
                            <input type="email" class="form-control" name="email_alternativo" placeholder="correo.alternativo@ejemplo.com">
                        </div>

                        <div class="three-columns">
                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-user"></i>
                                    Nombre(s) <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" name="nombres" placeholder="Nombre(s)" required>
                            </div>

                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-user"></i>
                                    Apellido Paterno <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" name="apellido_paterno" placeholder="Apellido Paterno" required>
                            </div>

                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-user"></i>
                                    Apellido Materno
                                </label>
                                <input type="text" class="form-control" name="apellido_materno" placeholder="Apellido Materno">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2 - Personal Data -->
            <div class="form-section">
                <div class="section-header">
                    <i class="fas fa-user-edit"></i>
                    Sección 2 – Datos Personales
                </div>
                <div class="section-content">
                    <div class="field-grid">
                        <div class="three-columns">
                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-venus-mars"></i>
                                    Sexo <span class="required">*</span>
                                </label>
                                <select class="form-select" name="sexo" required>
                                    <option value="">Seleccione...</option>
                                    <option value="masculino">Masculino</option>
                                    <option value="femenino">Femenino</option>
                                </select>
                            </div>

                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-calendar-alt"></i>
                                    Fecha de Nacimiento <span class="required">*</span>
                                </label>
                                <input type="date" class="form-control" name="fecha_nacimiento" required>
                            </div>

                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-globe-americas"></i>
                                    País de Nacimiento <span class="required">*</span>
                                </label>
                                <select class="form-select" name="pais_nacimiento" required>
                                    <option value="">Seleccione...</option>
                                    <option value="mexico">México</option>
                                    <option value="usa">Estados Unidos</option>
                                    <option value="canada">Canadá</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>
                        </div>

                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-map-marked-alt"></i>
                                Estado de Nacimiento <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" name="estado_nacimiento" placeholder="Estado de Nacimiento" required>
                        </div>

                        <div class="two-columns">
                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-phone"></i>
                                    Teléfono Casa
                                </label>
                                <input type="tel" class="form-control" name="telefono_casa" placeholder="(55) 1234-5678">
                            </div>

                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-mobile-alt"></i>
                                    Teléfono Móvil <span class="required">*</span>
                                </label>
                                <input type="tel" class="form-control" name="telefono_movil" placeholder="(55) 1234-5678" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3 - Address -->
            <div class="form-section">
                <div class="section-header">
                    <i class="fas fa-home"></i>
                    Sección 3 – Domicilio
                </div>
                <div class="section-content">
                    <div class="field-grid">
                        <div class="two-columns">
                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-map"></i>
                                    Estado <span class="required">*</span>
                                </label>
                                <select class="form-select" name="estado" id="estadoSelect" required>
                                    <option value="">Seleccione un estado...</option>
                                    <option value="aguascalientes">Aguascalientes</option>
                                    <option value="baja_california">Baja California</option>
                                    <option value="baja_california_sur">Baja California Sur</option>
                                    <option value="campeche">Campeche</option>
                                    <option value="coahuila">Coahuila</option>
                                    <option value="colima">Colima</option>
                                    <option value="chiapas">Chiapas</option>
                                    <option value="chihuahua">Chihuahua</option>
                                    <option value="ciudad_de_mexico">Ciudad de México</option>
                                    <option value="durango">Durango</option>
                                    <option value="guanajuato">Guanajuato</option>
                                    <option value="guerrero">Guerrero</option>
                                    <option value="hidalgo">Hidalgo</option>
                                    <option value="jalisco">Jalisco</option>
                                    <option value="estado_de_mexico">Estado de México</option>
                                    <option value="michoacan">Michoacán</option>
                                    <option value="morelos">Morelos</option>
                                    <option value="nayarit">Nayarit</option>
                                    <option value="nuevo_leon">Nuevo León</option>
                                    <option value="oaxaca">Oaxaca</option>
                                    <option value="puebla">Puebla</option>
                                    <option value="queretaro">Querétaro</option>
                                    <option value="quintana_roo">Quintana Roo</option>
                                    <option value="san_luis_potosi">San Luis Potosí</option>
                                    <option value="sinaloa">Sinaloa</option>
                                    <option value="sonora">Sonora</option>
                                    <option value="tabasco">Tabasco</option>
                                    <option value="tamaulipas">Tamaulipas</option>
                                    <option value="tlaxcala">Tlaxcala</option>
                                    <option value="veracruz">Veracruz</option>
                                    <option value="yucatan">Yucatán</option>
                                    <option value="zacatecas">Zacatecas</option>
                                </select>
                            </div>

                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-city"></i>
                                    Municipio <span class="required">*</span>
                                </label>
                                <select class="form-select" name="municipio" id="municipioSelect" required disabled>
                                    <option value="">Primero seleccione un estado...</option>
                                </select>
                            </div>
                        </div>

                        <div class="two-columns">
                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-map-pin"></i>
                                    Localidad <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" name="localidad" placeholder="Localidad" required>
                            </div>

                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-mail-bulk"></i>
                                    Código Postal <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" name="codigo_postal" placeholder="12345" maxlength="5" required>
                            </div>
                        </div>

                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-road"></i>
                                Calle <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" name="calle" placeholder="Nombre de la calle" required>
                        </div>

                        <div class="two-columns">
                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-hashtag"></i>
                                    Número Exterior <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" name="numero_exterior" placeholder="123" required>
                            </div>

                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-hashtag"></i>
                                    Número Interior
                                </label>
                                <input type="text" class="form-control" name="numero_interior" placeholder="Depto. 4B">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 4 - Face Verification -->
            <div class="form-section">
                <div class="section-header">
                    <i class="fas fa-shield-check"></i>
                    Sección 4 – Verificación de Identidad Facial
                </div>
                <div class="section-content">
                    <div class="field-grid">
                        <div class="field-group" id="faceVerificationStatus">
                            <div style="text-align: center; padding: 2rem;">
                                <div id="verificationPending" style="display: block;">
                                    <i class="fas fa-exclamation-triangle" style="font-size: 3rem; color: #f59e0b; margin-bottom: 1rem;"></i>
                                    <h3 style="color: #92400e; margin-bottom: 1rem;">Verificación Facial Requerida</h3>
                                    <p style="color: #64748b; margin-bottom: 2rem; max-width: 500px; margin-left: auto; margin-right: auto;">
                                        Para completar su registro, debe verificar su identidad comparando una selfie con la fotografía de su INE/IFE. 
                                        Este proceso garantiza la seguridad y autenticidad de su cuenta.
                                    </p>
                                    <a href="/face-verification" class="btn btn-primary" style="font-size: 1.1rem; padding: 1rem 2rem;">
                                        <i class="fas fa-camera"></i>
                                        Iniciar Verificación Facial
                                    </a>
                                </div>
                                
                                <div id="verificationComplete" style="display: none;">
                                    <i class="fas fa-check-circle" style="font-size: 3rem; color: #10b981; margin-bottom: 1rem;"></i>
                                    <h3 style="color: #065f46; margin-bottom: 1rem;">Verificación Facial Completada</h3>
                                    <p style="color: #64748b; margin-bottom: 1rem;">
                                        Su identidad ha sido verificada exitosamente. Puede continuar con el registro.
                                    </p>
                                    <div style="background: #f0fdf4; padding: 1rem; border-radius: 8px; border: 1px solid #bbf7d0; margin-top: 1rem;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem; color: #166534; font-weight: 600;">
                                            <i class="fas fa-shield-check"></i>
                                            Estado: Verificado
                                        </div>
                                        <div style="font-size: 0.9rem; color: #16a34a; margin-top: 0.5rem;">
                                            Confianza: <span id="verificationConfidence">--</span>%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="form-actions">
                <button type="button" class="btn btn-secondary">
                    <i class="fas fa-edit"></i>
                    Modificar
                </button>
                <button type="submit" class="btn btn-primary" id="finalSubmitBtn">
                    <i class="fas fa-arrow-right"></i>
                    Continuar
                </button>
            </div>

            <!-- Loading State -->
            <div class="form-loading" id="loadingState">
                <div class="spinner"></div>
                <p>Procesando su registro...</p>
            </div>
        </form>

        <div class="login-redirect">
            <p><i class="fas fa-sign-in-alt"></i> ¿Ya tienes una cuenta? <a href="/login">Inicia sesión aquí</a></p>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const curpInput = document.getElementById('curpInput');
            const curpMessage = document.getElementById('curpValidationMessage');
            
            // CURP format validation regex
            const curpRegex = /^[A-Z]{1}[AEIOUX]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01])[HM]{1}[A-Z]{2}[BCDFGHJKLMNPQRSTVWXYZ]{3}[0-9A-Z]{1}[0-9]{1}$/;
            
            if (curpInput) {
                curpInput.addEventListener('input', function() {
                    let value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
                    this.value = value;
                    
                    if (value.length === 0) {
                        hideCurpMessage();
                        return;
                    }
                    
                    if (value.length !== 18) {
                        showCurpMessage('error', '<i class="fas fa-exclamation-circle"></i> CURP debe tener exactamente 18 caracteres');
                        return;
                    }
                    
                    if (!curpRegex.test(value)) {
                        showCurpMessage('error', '<i class="fas fa-times-circle"></i> Formato de CURP inválido');
                        return;
                    }
                    
                    showCurpMessage('success', '<i class="fas fa-check-circle"></i> Formato de CURP válido');
                });
            }
            
            function showCurpMessage(type, message) {
                curpMessage.style.display = 'flex';
                curpMessage.style.alignItems = 'center';
                curpMessage.style.gap = '0.5rem';
                curpMessage.innerHTML = message;
                
                if (type === 'success') {
                    curpMessage.style.color = '#10B981';
                    curpInput.style.borderColor = '#10B981';
                    curpInput.style.backgroundColor = '#f0fdf4';
                } else {
                    curpMessage.style.color = '#EF4444';
                    curpInput.style.borderColor = '#EF4444';
                    curpInput.style.backgroundColor = '#fef2f2';
                }
            }
            
            function hideCurpMessage() {
                curpMessage.style.display = 'none';
                curpInput.style.borderColor = '';
                curpInput.style.backgroundColor = '';
            }
        });

        // Function to handle CURP validation from registry
        function validateCurpFromRegistry() {
            const curpInput = document.getElementById('curpInput');
            const curp = curpInput.value.trim().toUpperCase();
            
            if (!curp) {
                alert('Por favor ingrese un CURP antes de validar');
                curpInput.focus();
                return;
            }
            
            if (curp.length !== 18) {
                alert('El CURP debe tener exactamente 18 caracteres');
                curpInput.focus();
                return;
            }
            
            // Store current form data in sessionStorage before redirecting
            const formData = {
                curp: curp,
                // Store other form fields that might be filled
                nombre: document.getElementById('nombre')?.value || '',
                apellidoPaterno: document.getElementById('apellidoPaterno')?.value || '',
                apellidoMaterno: document.getElementById('apellidoMaterno')?.value || '',
                email: document.getElementById('email')?.value || '',
                telefono: document.getElementById('telefono')?.value || '',
                return_url: window.location.href
            };
            
            sessionStorage.setItem('registryFormData', JSON.stringify(formData));
            
            // Redirect to CURP validation page
            window.location.href = '/curp/validate?from=registry&curp=' + encodeURIComponent(curp);
        }

        // Function to handle return from CURP validation
        function handleCurpValidationReturn() {
            console.log('🔍 Checking for CURP validation return data...');
            const urlParams = new URLSearchParams(window.location.search);
            const verificationData = urlParams.get('verification');
            const source = urlParams.get('source');
            
            console.log('URL parameters:', window.location.search);
            console.log('Verification data found:', !!verificationData);
            console.log('Source:', source);
            
            if (verificationData) {
                console.log('Raw verification data:', verificationData);
                
                try {
                    const data = JSON.parse(decodeURIComponent(verificationData));
                    console.log('Parsed verification data:', data);
                    
                    if (data.success && data.data) {
                        console.log('✅ Valid data found, auto-filling form...');
                        console.log('Data details:', data.data.details);
                        
                        // Auto-fill form with verified data
                        autoFillFormWithCurpData(data.data);
                        
                        // Show success message
                        const curpMessage = document.getElementById('curpValidationMessage');
                        if (curpMessage) {
                            if (source === 'curp') {
                                showCurpMessage('success', '<i class="fas fa-check-circle"></i> CURP verificado desde login - Cuenta creada automáticamente con datos oficiales');
                            } else {
                                showCurpMessage('success', '<i class="fas fa-check-circle"></i> CURP verificado exitosamente - Datos auto-completados');
                            }
                        }
                        
                        // For CURP-based registration from login, show welcome message
                        if (source === 'curp') {
                            const welcomeMessage = document.createElement('div');
                            welcomeMessage.style.cssText = 'background: #f0fdf4; border: 1px solid #10B981; border-radius: 8px; padding: 1rem; margin: 1rem 0; color: #065f46;';
                            welcomeMessage.innerHTML = `
                                <div style="display: flex; align-items: center; gap: 0.5rem; font-weight: 600; margin-bottom: 0.5rem;">
                                    <i class="fas fa-shield-check"></i>
                                    ¡Bienvenido al sistema MARINA!
                                </div>
                                <p style="font-size: 0.9rem; margin: 0;">Su identidad ha sido verificada con CURP. Complete los campos restantes para finalizar su registro.</p>
                            `;
                            document.querySelector('.registration-form').prepend(welcomeMessage);
                        }
                        
                        // Clear URL parameters
                        window.history.replaceState({}, document.title, window.location.pathname);
                    } else {
                        console.log('❌ Invalid data structure:', data);
                    }
                } catch (e) {
                    console.error('❌ Error parsing verification data:', e);
                }
            } else {
                console.log('ℹ️ No verification data found in URL');
            }
        }

        // Function to auto-fill form with CURP data
        function autoFillFormWithCurpData(data) {
            console.log('📝 Starting auto-fill with data:', data);
            const details = data.details || {};
            console.log('📋 Details object:', details);
            
            // Fill CURP
            const curpInput = document.querySelector('input[name="curp"]');
            console.log('CURP input found:', !!curpInput);
            if (curpInput && data.curp) {
                curpInput.value = data.curp;
                console.log('✅ CURP filled:', data.curp);
            }
            
            // Fill name fields if available
            if (details.nombres) {
                const nombreInput = document.querySelector('input[name="nombres"]');
                console.log('Nombres input found:', !!nombreInput);
                if (nombreInput && !nombreInput.value) {
                    nombreInput.value = details.nombres;
                    console.log('✅ Nombres filled:', details.nombres);
                }
            }
            
            if (details.primerApellido) {
                const apellidoPaternoInput = document.querySelector('input[name="apellido_paterno"]');
                console.log('Apellido paterno input found:', !!apellidoPaternoInput);
                if (apellidoPaternoInput && !apellidoPaternoInput.value) {
                    apellidoPaternoInput.value = details.primerApellido;
                    console.log('✅ Apellido paterno filled:', details.primerApellido);
                }
            }
            
            if (details.segundoApellido) {
                const apellidoMaternoInput = document.querySelector('input[name="apellido_materno"]');
                console.log('Apellido materno input found:', !!apellidoMaternoInput);
                if (apellidoMaternoInput && !apellidoMaternoInput.value) {
                    apellidoMaternoInput.value = details.segundoApellido;
                    console.log('✅ Apellido materno filled:', details.segundoApellido);
                }
            }
            
            // Fill birth date if available
            if (details.fechaNacimiento) {
                const fechaNacimientoInput = document.querySelector('input[name="fecha_nacimiento"]');
                console.log('Fecha nacimiento input found:', !!fechaNacimientoInput);
                if (fechaNacimientoInput && !fechaNacimientoInput.value) {
                    fechaNacimientoInput.value = details.fechaNacimiento;
                    console.log('✅ Fecha nacimiento filled:', details.fechaNacimiento);
                }
            }
            
            // Fill gender if available
            if (details.sexo) {
                const sexoSelect = document.querySelector('select[name="sexo"]');
                console.log('Sexo select found:', !!sexoSelect);
                if (sexoSelect && !sexoSelect.value) {
                    const sexoValue = details.sexo.toLowerCase().includes('masculino') ? 'masculino' : 
                                     details.sexo.toLowerCase().includes('femenino') ? 'femenino' : '';
                    if (sexoValue) {
                        sexoSelect.value = sexoValue;
                        console.log('✅ Sexo filled:', sexoValue);
                    }
                }
            }
            
            // Fill state of birth if available
            if (details.entidadNacimiento) {
                const estadoNacimientoInput = document.querySelector('input[name="estado_nacimiento"]');
                console.log('Estado nacimiento input found:', !!estadoNacimientoInput);
                if (estadoNacimientoInput && !estadoNacimientoInput.value) {
                    estadoNacimientoInput.value = details.entidadNacimiento;
                    console.log('✅ Estado nacimiento filled:', details.entidadNacimiento);
                }
            }
            
            console.log('📝 Auto-fill process completed');
        }

        // Function to handle registration method selection
        function selectRegistrationMethod(method) {
            const methodsSection = document.getElementById('registrationMethods');
            const form = document.getElementById('registryForm');
            
            if (method === 'traditional') {
                // Hide method selection and show form
                methodsSection.style.display = 'none';
                form.style.display = 'block';
                
                // Scroll to form
                form.scrollIntoView({ behavior: 'smooth' });
            } else if (method === 'curp') {
                // Redirect to CURP validation
                window.location.href = '/curp/validate?from=login';
            }
        }

        // Function to check if user came from CURP validation
        function checkCurpReturn() {
            const urlParams = new URLSearchParams(window.location.search);
            const verification = urlParams.get('verification');
            const source = urlParams.get('source');
            
            if (verification && source === 'curp') {
                // User came from CURP validation, hide method selection and show form
                document.getElementById('registrationMethods').style.display = 'none';
                document.getElementById('registryForm').style.display = 'block';
            }
        }

        // State-Municipality relationship data
        const municipalitiesData = {
            'aguascalientes': [
                'Aguascalientes', 'Asientos', 'Calvillo', 'Cosío', 'Jesús María',
                'Pabellón de Arteaga', 'Rincón de Romos', 'San José de Gracia', 'Tepezalá', 'El Llano'
            ],
            'baja_california': [
                'Tijuana', 'Mexicali', 'Ensenada', 'Tecate', 'Playas de Rosarito'
            ],
            'baja_california_sur': [
                'La Paz', 'Los Cabos', 'Loreto', 'Comondú', 'Mulegé'
            ],
            'campeche': [
                'Campeche', 'Carmen', 'Champotón', 'Escárcega', 'Hecelchakán',
                'Hopelchén', 'Palizada', 'Tenabo', 'Calakmul', 'Candelaria'
            ],
            'coahuila': [
                'Saltillo', 'Torreón', 'Monclova', 'Piedras Negras', 'Acuña',
                'Sabinas', 'Matamoros', 'San Pedro', 'Frontera', 'Múzquiz'
            ],
            'colima': [
                'Colima', 'Manzanillo', 'Tecomán', 'Armería', 'Comala',
                'Coquimatlán', 'Cuauhtémoc', 'Ixtlahuacán', 'Minatitlán', 'Villa de Álvarez'
            ],
            'chiapas': [
                'Tuxtla Gutiérrez', 'Tapachula', 'San Cristóbal de las Casas', 'Comitán', 'Palenque',
                'Tonalá', 'Villaflores', 'Pichucalco', 'Ocosingo', 'Arriaga'
            ],
            'chihuahua': [
                'Chihuahua', 'Ciudad Juárez', 'Delicias', 'Parral', 'Cuauhtémoc',
                'Nuevo Casas Grandes', 'Camargo', 'Jiménez', 'Bocoyna', 'Meoqui'
            ],
            'ciudad_de_mexico': [
                'Álvaro Obregón', 'Azcapotzalco', 'Benito Juárez', 'Coyoacán', 'Cuajimalpa',
                'Gustavo A. Madero', 'Iztacalco', 'Iztapalapa', 'Miguel Hidalgo', 'Tlalpan',
                'Venustiano Carranza', 'Xochimilco', 'Milpa Alta', 'Cuauhtémoc', 'Tláhuac', 'Magdalena Contreras'
            ],
            'durango': [
                'Durango', 'Gómez Palacio', 'Lerdo', 'Santiago Papasquiaro', 'Guadalupe Victoria',
                'Canatlán', 'Nombre de Dios', 'Mezquital', 'Tlahualilo', 'Rodeo'
            ],
            'guanajuato': [
                'León', 'Irapuato', 'Celaya', 'Salamanca', 'Guanajuato',
                'Silao', 'Pénjamo', 'San Miguel de Allende', 'Acámbaro', 'Dolores Hidalgo'
            ],
            'guerrero': [
                'Acapulco', 'Chilpancingo', 'Iguala', 'Taxco', 'Zihuatanejo',
                'Tlapa', 'Ayutla de los Libres', 'Ometepec', 'Petatlán', 'Arcelia'
            ],
            'hidalgo': [
                'Pachuca', 'Tulancingo', 'Huejutla', 'Ixmiquilpan', 'Tizayuca',
                'Actopan', 'Tepeapulco', 'Mineral de la Reforma', 'Tula', 'Apan'
            ],
            'jalisco': [
                'Guadalajara', 'Zapopan', 'Tlaquepaque', 'Tonalá', 'Puerto Vallarta',
                'Tlajomulco de Zúñiga', 'El Salto', 'Chapala', 'Lagos de Moreno', 'Tepatitlán',
                'Ocotlán', 'Arandas', 'La Barca', 'Ameca', 'Autlán'
            ],
            'estado_de_mexico': [
                'Ecatepec', 'Nezahualcóyotl', 'Naucalpan', 'Tlalnepantla', 'Chimalhuacán',
                'Toluca', 'Atizapán de Zaragoza', 'Cuautitlán Izcalli', 'Ixtapaluca', 'Tultitlán',
                'Chalco', 'Texcoco', 'Metepec', 'La Paz', 'Coacalco'
            ],
            'michoacan': [
                'Morelia', 'Uruapan', 'Lázaro Cárdenas', 'Zamora', 'Apatzingán',
                'Pátzcuaro', 'Sahuayo', 'Zitácuaro', 'Hidalgo', 'La Piedad'
            ],
            'morelos': [
                'Cuernavaca', 'Jiutepec', 'Temixco', 'Cuautla', 'Emiliano Zapata',
                'Yautepec', 'Xochitepec', 'Zacatepec', 'Jojutla', 'Tepoztlán'
            ],
            'nayarit': [
                'Tepic', 'Bahía de Banderas', 'Santiago Ixcuintla', 'Tuxpan', 'Compostela',
                'Ixtlán del Río', 'Acaponeta', 'Rosamorada', 'Ruiz', 'Tecuala'
            ],
            'nuevo_leon': [
                'Monterrey', 'Guadalupe', 'San Nicolás de los Garza', 'Apodaca', 'Santa Catarina',
                'San Pedro Garza García', 'Escobedo', 'Cadereyta Jiménez', 'Juárez', 'García',
                'Linares', 'Montemorelos', 'Sabinas Hidalgo', 'Cerralvo', 'China'
            ],
            'oaxaca': [
                'Oaxaca de Juárez', 'Salina Cruz', 'Tuxtepec', 'Juchitán', 'Huajuapan',
                'Ixtepec', 'Pochutla', 'Tehuantepec', 'Pinotepa Nacional', 'Miahuatlán'
            ],
            'puebla': [
                'Puebla', 'Tehuacán', 'San Martín Texmelucan', 'Atlixco', 'San Pedro Cholula',
                'Huauchinango', 'Amozoc', 'Teziutlán', 'Cuautlancingo', 'Zacatlán'
            ],
            'queretaro': [
                'Querétaro', 'San Juan del Río', 'Corregidora', 'El Marqués', 'Cadereyta',
                'Tequisquiapan', 'Pedro Escobedo', 'Amealco', 'Jalpan', 'Landa de Matamoros'
            ],
            'quintana_roo': [
                'Cancún', 'Chetumal', 'Playa del Carmen', 'Cozumel', 'Felipe Carrillo Puerto',
                'José María Morelos', 'Lázaro Cárdenas', 'Othón P. Blanco', 'Solidaridad', 'Tulum'
            ],
            'san_luis_potosi': [
                'San Luis Potosí', 'Soledad de Graciano Sánchez', 'Ciudad Valles', 'Matehuala', 'Rioverde',
                'Tamazunchale', 'Cárdenas', 'Ebano', 'Guadalcázar', 'Mexquitic'
            ],
            'sinaloa': [
                'Culiacán', 'Mazatlán', 'Los Mochis', 'Guasave', 'Navolato',
                'El Fuerte', 'Escuinapa', 'Salvador Alvarado', 'Angostura', 'Mocorito',
                'Choix', 'Elota', 'Concordia', 'Rosario', 'Cosalá'
            ],
            'sonora': [
                'Hermosillo', 'Ciudad Obregón', 'Nogales', 'San Luis Río Colorado', 'Navojoa',
                'Guaymas', 'Agua Prieta', 'Caborca', 'Puerto Peñasco', 'Cananea'
            ],
            'tabasco': [
                'Villahermosa', 'Cárdenas', 'Comalcalco', 'Huimanguillo', 'Macuspana',
                'Teapa', 'Jalpa de Méndez', 'Cunduacán', 'Balancán', 'Emiliano Zapata'
            ],
            'tamaulipas': [
                'Reynosa', 'Matamoros', 'Nuevo Laredo', 'Tampico', 'Victoria',
                'Altamira', 'Río Bravo', 'Valle Hermoso', 'Ciudad Madero', 'Miguel Alemán'
            ],
            'tlaxcala': [
                'Tlaxcala', 'Apizaco', 'Huamantla', 'Zacatelco', 'Santa Ana Chiautempan',
                'Calpulalpan', 'Panotla', 'San Pablo del Monte', 'Chiautempan', 'Tetla'
            ],
            'veracruz': [
                'Veracruz', 'Xalapa', 'Coatzacoalcos', 'Córdoba', 'Poza Rica', 
                'Minatitlán', 'Orizaba', 'Boca del Río', 'Tuxpan', 'Papantla',
                'Martínez de la Torre', 'San Andrés Tuxtla', 'Acayucan', 'Tantoyuca', 'Perote'
            ],
            'yucatan': [
                'Mérida', 'Kanasín', 'Valladolid', 'Progreso', 'Tizimín',
                'Motul', 'Uman', 'Tekax', 'Izamal', 'Hunucmá'
            ],
            'zacatecas': [
                'Zacatecas', 'Fresnillo', 'Guadalupe', 'Jerez', 'Río Grande',
                'Sombrerete', 'Ojocaliente', 'Tlaltenango', 'Juchipila', 'Nochistlán'
            ]
        };

        // Function to update municipalities based on selected state
        function updateMunicipalities() {
            const estadoSelect = document.getElementById('estadoSelect');
            const municipioSelect = document.getElementById('municipioSelect');
            
            if (!estadoSelect || !municipioSelect) {
                console.log('❌ Estado or Municipio select not found');
                return;
            }
            
            const selectedState = estadoSelect.value;
            console.log('🔍 Selected state:', selectedState);
            
            // Clear current options
            municipioSelect.innerHTML = '';
            
            if (!selectedState) {
                municipioSelect.disabled = true;
                municipioSelect.innerHTML = '<option value="">Primero seleccione un estado...</option>';
                console.log('ℹ️ No state selected, municipality disabled');
                return;
            }
            
            // Get municipalities for selected state
            const municipalities = municipalitiesData[selectedState];
            console.log('🏛️ Municipalities found:', municipalities ? municipalities.length : 0);
            
            if (municipalities && municipalities.length > 0) {
                municipioSelect.disabled = false;
                municipioSelect.innerHTML = '<option value="">Seleccione un municipio...</option>';
                
                municipalities.forEach(municipality => {
                    const option = document.createElement('option');
                    option.value = municipality.toLowerCase().replace(/\s+/g, '_').replace(/ñ/g, 'n');
                    option.textContent = municipality;
                    municipioSelect.appendChild(option);
                });
                
                console.log('✅ Municipalities loaded successfully for:', selectedState);
            } else {
                // This should not happen now as we have all states
                console.log('❌ No municipalities found for state:', selectedState);
                municipioSelect.disabled = false;
                municipioSelect.innerHTML = `
                    <option value="">Seleccione un municipio...</option>
                    <option value="otro">Otro municipio</option>
                `;
            }
        }

        // Initialize state-municipality functionality
        function initializeStateMunicipalityRelationship() {
            const estadoSelect = document.getElementById('estadoSelect');
            
            if (estadoSelect) {
                estadoSelect.addEventListener('change', updateMunicipalities);
                console.log('✅ State-Municipality relationship initialized');
            }
        }

        // Face verification status checking
        function checkFaceVerificationStatus() {
            const urlParams = new URLSearchParams(window.location.search);
            const faceVerified = urlParams.get('face_verified');
            const confidence = urlParams.get('confidence');
            
            console.log('🔍 Checking face verification status:', { faceVerified, confidence });
            
            if (faceVerified === 'true') {
                // Show verified status
                document.getElementById('verificationPending').style.display = 'none';
                document.getElementById('verificationComplete').style.display = 'block';
                
                if (confidence) {
                    document.getElementById('verificationConfidence').textContent = confidence;
                }
                
                // Enable final submit button
                const submitBtn = document.getElementById('finalSubmitBtn');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check-circle"></i> Completar Registro';
                submitBtn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                
                console.log('✅ Face verification confirmed, registration can proceed');
                
                // Clean URL parameters
                window.history.replaceState({}, document.title, window.location.pathname);
            } else {
                // Disable submit button until verification is complete
                const submitBtn = document.getElementById('finalSubmitBtn');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Verificación Facial Requerida';
                submitBtn.style.background = 'linear-gradient(135deg, #64748b, #475569)';
                
                console.log('⚠️ Face verification required before registration completion');
            }
        }

        // Form submission validation
        function validateRegistrationForm(event) {
            const urlParams = new URLSearchParams(window.location.search);
            const faceVerified = urlParams.get('face_verified');
            
            // Check if face verification is complete
            if (faceVerified !== 'true') {
                event.preventDefault();
                
                // Show alert
                const alertDiv = document.createElement('div');
                alertDiv.style.cssText = 'background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 1rem; margin: 1rem 0; color: #991b1b;';
                alertDiv.innerHTML = `
                    <div style="display: flex; align-items: center; gap: 0.5rem; font-weight: 600;">
                        <i class="fas fa-exclamation-triangle"></i>
                        Verificación Facial Requerida
                    </div>
                    <p style="margin: 0.5rem 0 0 0;">Debe completar la verificación facial antes de enviar el registro.</p>
                `;
                
                // Insert alert before form actions
                const formActions = document.querySelector('.form-actions');
                formActions.parentNode.insertBefore(alertDiv, formActions);
                
                // Scroll to face verification section
                document.getElementById('faceVerificationStatus').scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
                
                // Remove alert after 5 seconds
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.parentNode.removeChild(alertDiv);
                    }
                }, 5000);
                
                return false;
            }
            
            return true;
        }

        // Initialize handlers when page loads
        document.addEventListener('DOMContentLoaded', function() {
            checkCurpReturn();
            handleCurpValidationReturn();
            initializeStateMunicipalityRelationship();
            checkFaceVerificationStatus();
            
            // Add form submission validation
            const form = document.getElementById('registryForm');
            if (form) {
                form.addEventListener('submit', validateRegistrationForm);
            }
        });
    </script>
</body>
</html>