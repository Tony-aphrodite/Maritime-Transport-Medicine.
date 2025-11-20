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

        /* Password Toggle Button */
        .password-toggle-btn {
            padding: 12px 16px;
            background: linear-gradient(135deg, #64748b, #475569);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 48px;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .password-toggle-btn:hover {
            background: linear-gradient(135deg, #475569, #334155);
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(100, 116, 139, 0.3);
        }

        .password-toggle-btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(100, 116, 139, 0.2);
        }

        .password-toggle-btn i {
            transition: all 0.2s ease;
        }

        .password-toggle-btn.showing-password {
            background: linear-gradient(135deg, #0F4C75, #3282B8);
        }

        .password-toggle-btn.showing-password:hover {
            background: linear-gradient(135deg, #0A3A5C, #0F4C75);
        }

        /* Ensure password inputs remain accessible during validation */
        .input-with-button .form-control {
            pointer-events: auto !important;
            z-index: 1;
            position: relative;
        }

        .input-with-button .form-control:focus {
            outline: none;
            border-color: #0F4C75 !important;
            box-shadow: 0 0 0 3px rgba(15, 76, 117, 0.1) !important;
        }

        /* RFC Input Styling */
        .rfc-input-container {
            display: flex;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .rfc-input-container:focus-within {
            border-color: #0F4C75;
            box-shadow: 0 0 0 3px rgba(15, 76, 117, 0.1);
        }

        .rfc-readonly-section {
            background: #f8fafc;
            border-right: 1px solid #e5e7eb;
            padding: 0.75rem;
            display: flex;
            align-items: center;
            min-width: 120px;
        }

        .rfc-readonly-text {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            color: #374151;
            letter-spacing: 1px;
            font-size: 1rem;
        }

        .rfc-editable-section {
            flex: 1;
            position: relative;
        }

        .rfc-editable-input {
            border: none !important;
            box-shadow: none !important;
            padding: 0.75rem;
            width: 100%;
            font-family: 'Courier New', monospace;
            font-weight: 600;
            letter-spacing: 1px;
            font-size: 1rem;
            background: white;
        }

        .rfc-editable-input:focus {
            outline: none;
            background: #fafbfc;
        }

        .rfc-help-text {
            font-size: 0.8rem;
            color: #6b7280;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .rfc-help-text i {
            color: #9ca3af;
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
            
            @if($errors->any())
                <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 1rem; margin: 1rem 0; color: #991b1b;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; font-weight: 600; margin-bottom: 0.5rem;">
                        <i class="fas fa-exclamation-triangle"></i>
                        Error en el registro
                    </div>
                    <ul style="margin: 0; padding-left: 1.5rem;">
                        @foreach($errors->all() as $error)
                            <li style="margin: 0.25rem 0;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Registration Method Selection - COMPLETELY REMOVED - Always show form directly -->

        <form action="{{ route('registro.submit', [], request()->isSecure()) }}" method="POST" id="registryForm" style="display: block;">
            @csrf
            <input type="hidden" name="face_verified" id="faceVerifiedInput" value="">
            <input type="hidden" name="face_verification_confidence" id="faceConfidenceInput" value="">
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

                        <div class="field-group" id="expedienteMedicoField" style="display: none;">
                            <label class="field-label">
                                <i class="fas fa-folder-open"></i>
                                Expediente Médico <span class="required">*</span>
                            </label>
                            <div class="input-with-button">
                                <input type="text" class="form-control" name="expediente_medico" id="expedienteMedicoInput" placeholder="Número de expediente médico">
                                <button type="button" onclick="searchMedicalRecord()" class="search-btn">
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
                                    RFC <span class="required">*</span>
                                </label>
                                <div class="rfc-input-container">
                                    <div class="rfc-readonly-section" id="rfcReadonlySection">
                                        <span id="rfcFromCurp" class="rfc-readonly-text">--</span>
                                    </div>
                                    <div class="rfc-editable-section">
                                        <input type="text" 
                                               class="form-control rfc-editable-input" 
                                               name="rfc_suffix" 
                                               id="rfcSuffixInput"
                                               placeholder="XXX" 
                                               maxlength="3"
                                               style="text-transform: uppercase;">
                                        <input type="hidden" name="rfc" id="rfcHiddenInput">
                                    </div>
                                </div>
                                <div class="rfc-help-text">
                                    <i class="fas fa-info-circle"></i>
                                    Los primeros 10 caracteres se toman del CURP validado. Solo capture los últimos 3 dígitos.
                                </div>
                                <div id="rfcValidationMessage" style="margin-top: 0.5rem; font-size: 0.875rem; display: none;"></div>
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

                        <div class="two-columns">
                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-lock"></i>
                                    Contraseña <span class="required">*</span>
                                </label>
                                <div class="input-with-button">
                                    <input type="password" class="form-control" name="password" id="passwordField" placeholder="Mínimo 8 caracteres" minlength="8" required>
                                    <button type="button" onclick="togglePasswordVisibility('passwordField', this)" class="password-toggle-btn" title="Mostrar contraseña">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div style="font-size: 0.8rem; color: #64748b; margin-top: 0.25rem;">
                                    <i class="fas fa-info-circle"></i> Al menos 8 caracteres, incluya mayúsculas, minúsculas y números
                                </div>
                            </div>

                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-lock"></i>
                                    Confirmar Contraseña <span class="required">*</span>
                                </label>
                                <div class="input-with-button">
                                    <input type="password" class="form-control" name="password_confirmation" id="passwordConfirmField" placeholder="Confirme su contraseña" minlength="8" required>
                                    <button type="button" onclick="togglePasswordVisibility('passwordConfirmField', this)" class="password-toggle-btn" title="Mostrar contraseña">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div id="passwordMatchMessage" style="margin-top: 0.5rem; font-size: 0.875rem; display: none;"></div>
                            </div>
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
                                <input type="date" class="form-control" name="fecha_nacimiento" id="birthdateField" required>
                                <div id="ageVerificationMessage" style="margin-top: 0.5rem; font-size: 0.875rem; display: none;"></div>
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

            <!-- Section 4 - Parental Consent (for minors under 18) -->
            <div class="form-section" id="parentalConsentSection" style="display: none;">
                <div class="section-header">
                    <i class="fas fa-user-shield"></i>
                    Sección 4 – Consentimiento Parental (Menor de 18 años)
                </div>
                <div class="section-content">
                    <div class="field-grid">
                        <div class="alert alert-info" style="background: #e0f2fe; border: 1px solid #0288d1; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <i class="fas fa-info-circle" style="color: #0288d1; font-size: 1.2rem;"></i>
                                <strong style="color: #01579b;">Consentimiento Parental Requerido</strong>
                            </div>
                            <p style="margin: 0; color: #0277bd; line-height: 1.5;">
                                Como eres menor de 18 años, necesitamos el consentimiento de tu padre, madre o tutor legal 
                                para completar tu registro. Por favor proporciona la información de contacto de tu 
                                padre/madre/tutor legal.
                            </p>
                        </div>

                        <div class="two-columns">
                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-user"></i>
                                    Nombre Completo del Padre/Madre/Tutor <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" name="parent_full_name" id="parentFullNameField" placeholder="Nombre completo del padre/madre/tutor">
                            </div>

                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-envelope"></i>
                                    Correo Electrónico del Padre/Madre/Tutor <span class="required">*</span>
                                </label>
                                <input type="email" class="form-control" name="parent_email" id="parentEmailField" placeholder="correo.padre@ejemplo.com">
                            </div>
                        </div>

                        <div class="two-columns">
                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-phone"></i>
                                    Teléfono del Padre/Madre/Tutor
                                </label>
                                <input type="tel" class="form-control" name="parent_phone" id="parentPhoneField" placeholder="(555) 123-4567">
                            </div>

                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-users"></i>
                                    Relación <span class="required">*</span>
                                </label>
                                <select class="form-select" name="parent_relationship" id="parentRelationshipField">
                                    <option value="">Seleccione...</option>
                                    <option value="padre">Padre</option>
                                    <option value="madre">Madre</option>
                                    <option value="tutor_legal">Tutor Legal</option>
                                    <option value="abuelo">Abuelo/a</option>
                                    <option value="tio">Tío/a</option>
                                    <option value="otro">Otro Familiar</option>
                                </select>
                            </div>
                        </div>

                        <div class="alert alert-warning" style="background: #fff3e0; border: 1px solid #ff9800; border-radius: 8px; padding: 1rem; margin-top: 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <i class="fas fa-exclamation-triangle" style="color: #ff9800; font-size: 1.1rem;"></i>
                                <strong style="color: #e65100;">Proceso de Consentimiento</strong>
                            </div>
                            <p style="margin: 0; color: #ef6c00; line-height: 1.5; font-size: 0.9rem;">
                                • Se enviará un correo electrónico al padre/madre/tutor para solicitar consentimiento<br>
                                • El padre/madre/tutor debe aprobar el registro en un plazo de 7 días<br>
                                • Recibirás una notificación cuando se apruebe tu registro<br>
                                • Tu cuenta estará pendiente hasta recibir la aprobación parental
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 5 - Face Verification -->
            <div class="form-section">
                <div class="section-header">
                    <i class="fas fa-shield-check"></i>
                    Sección 5 – Verificación de Identidad Facial
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
                                    <button type="button" onclick="saveFormDataAndRedirect()" class="btn btn-primary" style="font-size: 1.1rem; padding: 1rem 2rem; margin-right: 1rem;">
                                        <i class="fas fa-camera"></i>
                                        Iniciar Verificación Facial
                                    </button>
                                    <button type="button" onclick="simulateVerification()" class="btn btn-secondary" style="font-size: 1rem; padding: 1rem 1.5rem;">
                                        <i class="fas fa-code"></i>
                                        Simular Verificación (Test)
                                    </button>
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
            const passwordInput = document.querySelector('input[name="password"]');
            const confirmPasswordInput = document.querySelector('input[name="password_confirmation"]');
            const passwordMatchMessage = document.getElementById('passwordMatchMessage');
            const birthdateField = document.getElementById('birthdateField');
            const ageVerificationMessage = document.getElementById('ageVerificationMessage');
            
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

            // Password validation
            if (passwordInput) {
                passwordInput.addEventListener('input', validatePassword);
            }
            
            if (confirmPasswordInput) {
                confirmPasswordInput.addEventListener('input', validatePasswordMatch);
            }

            // Age verification for parental consent
            if (birthdateField) {
                birthdateField.addEventListener('change', checkAgeForParentalConsent);
            }

            // Medical record field visibility
            const medicalRecordRadios = document.querySelectorAll('input[name="tiene_expediente"]');
            if (medicalRecordRadios.length > 0) {
                medicalRecordRadios.forEach(radio => {
                    radio.addEventListener('change', toggleMedicalRecordField);
                });
            }
            
            function validatePassword() {
                const password = passwordInput.value;
                const hasUpperCase = /[A-Z]/.test(password);
                const hasLowerCase = /[a-z]/.test(password);
                const hasNumber = /[0-9]/.test(password);
                const minLength = password.length >= 8;
                
                let strength = 0;
                let message = '';
                let color = '';
                
                if (password.length === 0) {
                    passwordInput.style.borderColor = '';
                    passwordInput.style.backgroundColor = '';
                    return;
                }
                
                if (minLength) strength++;
                if (hasUpperCase) strength++;
                if (hasLowerCase) strength++;
                if (hasNumber) strength++;
                
                if (strength >= 4) {
                    message = '<i class="fas fa-check-circle"></i> Contraseña segura';
                    color = '#10B981';
                    passwordInput.style.borderColor = '#10B981';
                    passwordInput.style.backgroundColor = '#f0fdf4';
                } else if (strength >= 2) {
                    message = '<i class="fas fa-exclamation-triangle"></i> Contraseña débil - incluya mayúsculas, minúsculas y números';
                    color = '#f59e0b';
                    passwordInput.style.borderColor = '#f59e0b';
                    passwordInput.style.backgroundColor = '#fffbeb';
                } else {
                    message = '<i class="fas fa-times-circle"></i> Contraseña muy débil';
                    color = '#ef4444';
                    passwordInput.style.borderColor = '#ef4444';
                    passwordInput.style.backgroundColor = '#fef2f2';
                }
                
                // Update password hint - find the hint div after the input-with-button container
                const passwordContainer = passwordInput.closest('.input-with-button');
                const passwordHint = passwordContainer ? passwordContainer.nextElementSibling : passwordInput.nextElementSibling;
                if (passwordHint && passwordHint.style !== undefined) {
                    passwordHint.innerHTML = message;
                    passwordHint.style.color = color;
                }
                
                // Revalidate password match if confirm field has value
                if (confirmPasswordInput && confirmPasswordInput.value) {
                    validatePasswordMatch();
                }
            }
            
            function validatePasswordMatch() {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                
                if (confirmPassword.length === 0) {
                    passwordMatchMessage.style.display = 'none';
                    confirmPasswordInput.style.borderColor = '';
                    confirmPasswordInput.style.backgroundColor = '';
                    return;
                }
                
                if (password === confirmPassword) {
                    showPasswordMatchMessage('success', '<i class="fas fa-check-circle"></i> Las contraseñas coinciden');
                } else {
                    showPasswordMatchMessage('error', '<i class="fas fa-times-circle"></i> Las contraseñas no coinciden');
                }
            }
            
            function showPasswordMatchMessage(type, message) {
                passwordMatchMessage.style.display = 'flex';
                passwordMatchMessage.style.alignItems = 'center';
                passwordMatchMessage.style.gap = '0.5rem';
                passwordMatchMessage.innerHTML = message;
                
                if (type === 'success') {
                    passwordMatchMessage.style.color = '#10B981';
                    confirmPasswordInput.style.borderColor = '#10B981';
                    confirmPasswordInput.style.backgroundColor = '#f0fdf4';
                } else {
                    passwordMatchMessage.style.color = '#EF4444';
                    confirmPasswordInput.style.borderColor = '#EF4444';
                    confirmPasswordInput.style.backgroundColor = '#fef2f2';
                }
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

        // Function to toggle password visibility
        function togglePasswordVisibility(fieldId, buttonElement) {
            const passwordField = document.getElementById(fieldId);
            const icon = buttonElement.querySelector('i');
            
            if (passwordField.type === 'password') {
                // Show password
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
                buttonElement.classList.add('showing-password');
                buttonElement.setAttribute('title', 'Ocultar contraseña');
            } else {
                // Hide password
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
                buttonElement.classList.remove('showing-password');
                buttonElement.setAttribute('title', 'Mostrar contraseña');
            }
        }

        // Function to toggle medical record field visibility
        function toggleMedicalRecordField() {
            const medicalRecordField = document.getElementById('expedienteMedicoField');
            const medicalRecordInput = document.getElementById('expedienteMedicoInput');
            const selectedValue = document.querySelector('input[name="tiene_expediente"]:checked');
            
            if (selectedValue && selectedValue.value === 'si') {
                // Show the medical record field
                medicalRecordField.style.display = 'block';
                
                // Make the field required
                medicalRecordInput.setAttribute('required', 'required');
                
                // Scroll to the field smoothly
                setTimeout(() => {
                    medicalRecordField.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'nearest' 
                    });
                }, 100);
                
                console.log('✅ Medical record field shown and made required');
            } else {
                // Hide the medical record field
                medicalRecordField.style.display = 'none';
                
                // Remove the required attribute
                medicalRecordInput.removeAttribute('required');
                
                // Clear the input value
                medicalRecordInput.value = '';
                
                // Clear any existing medical record messages
                const existingMessage = medicalRecordField.querySelector('.medical-record-message');
                if (existingMessage) {
                    existingMessage.remove();
                }
                
                console.log('🔒 Medical record field hidden and not required');
            }
        }

        // Function to check age for parental consent requirement
        function checkAgeForParentalConsent() {
            const birthdateField = document.getElementById('birthdateField');
            const ageVerificationMessage = document.getElementById('ageVerificationMessage');
            const parentalConsentSection = document.getElementById('parentalConsentSection');
            
            if (!birthdateField.value) {
                ageVerificationMessage.style.display = 'none';
                parentalConsentSection.style.display = 'none';
                setParentalConsentRequired(false);
                return;
            }

            const birthDate = new Date(birthdateField.value);
            const today = new Date();
            const age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            
            // Adjust age if birthday hasn't occurred this year
            const actualAge = (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) ? age - 1 : age;
            
            console.log(`🎂 Age calculated: ${actualAge} years old`);
            
            if (actualAge < 18) {
                // Show parental consent section
                showAgeVerificationMessage('warning', `
                    <i class="fas fa-exclamation-triangle"></i> 
                    Menor de edad detectado (${actualAge} años). Se requiere consentimiento parental.
                `);
                parentalConsentSection.style.display = 'block';
                setParentalConsentRequired(true);
                
                // Scroll to parental consent section
                setTimeout(() => {
                    parentalConsentSection.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'start' 
                    });
                }, 100);
                
                console.log('🔒 Parental consent required for minor');
            } else {
                // Hide parental consent section
                showAgeVerificationMessage('success', `
                    <i class="fas fa-check-circle"></i> 
                    Usuario mayor de edad (${actualAge} años). No se requiere consentimiento parental.
                `);
                parentalConsentSection.style.display = 'none';
                setParentalConsentRequired(false);
                
                console.log('✅ User is of legal age');
            }
        }

        // Function to show age verification messages
        function showAgeVerificationMessage(type, message) {
            const ageVerificationMessage = document.getElementById('ageVerificationMessage');
            
            ageVerificationMessage.style.display = 'flex';
            ageVerificationMessage.style.alignItems = 'center';
            ageVerificationMessage.style.gap = '0.5rem';
            ageVerificationMessage.innerHTML = message;
            
            switch (type) {
                case 'success':
                    ageVerificationMessage.style.color = '#10B981';
                    ageVerificationMessage.style.background = '#f0fdf4';
                    ageVerificationMessage.style.border = '1px solid #22c55e';
                    break;
                case 'warning':
                    ageVerificationMessage.style.color = '#f59e0b';
                    ageVerificationMessage.style.background = '#fffbeb';
                    ageVerificationMessage.style.border = '1px solid #f59e0b';
                    break;
                case 'error':
                    ageVerificationMessage.style.color = '#ef4444';
                    ageVerificationMessage.style.background = '#fef2f2';
                    ageVerificationMessage.style.border = '1px solid #ef4444';
                    break;
            }
            
            ageVerificationMessage.style.padding = '0.75rem';
            ageVerificationMessage.style.borderRadius = '6px';
            ageVerificationMessage.style.marginTop = '0.5rem';
            
            // Auto-hide success message after 5 seconds
            if (type === 'success') {
                setTimeout(() => {
                    ageVerificationMessage.style.display = 'none';
                }, 5000);
            }
        }

        // Function to set parental consent requirement status
        function setParentalConsentRequired(required) {
            window.parentalConsentRequired = required;
            
            // Toggle required attribute on parental consent fields
            const parentalFields = [
                'parentFullNameField',
                'parentEmailField', 
                'parentRelationshipField'
            ];
            
            parentalFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    if (required) {
                        field.setAttribute('required', 'required');
                    } else {
                        field.removeAttribute('required');
                        field.value = ''; // Clear values when not required
                    }
                }
            });
            
            console.log(`🔐 Parental consent requirement set to: ${required}`);
        }

        // Function to search medical record
        function searchMedicalRecord() {
            const expedienteInput = document.querySelector('input[name="expediente_medico"]');
            const expedienteNumber = expedienteInput.value.trim();
            
            if (!expedienteNumber) {
                alert('Por favor ingrese un número de expediente médico antes de buscar');
                expedienteInput.focus();
                return;
            }
            
            // Validate expediente format (basic validation - adjust as needed)
            if (expedienteNumber.length < 3) {
                alert('El número de expediente debe tener al menos 3 caracteres');
                expedienteInput.focus();
                return;
            }
            
            // Show loading state
            const searchBtn = event.target;
            const originalText = searchBtn.innerHTML;
            searchBtn.disabled = true;
            searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Buscando...';
            
            // Simulate API call for medical record search
            setTimeout(() => {
                // Reset button state
                searchBtn.disabled = false;
                searchBtn.innerHTML = originalText;
                
                // For demonstration - in production this would be a real API call
                const mockMedicalData = {
                    'EXP123456': {
                        nombre: 'Juan Carlos',
                        apellido_paterno: 'García',
                        apellido_materno: 'López',
                        fecha_nacimiento: '1985-04-15',
                        telefono: '5551234567',
                        email: 'juan.garcia@email.com',
                        found: true
                    },
                    'EXP789012': {
                        nombre: 'María Elena',
                        apellido_paterno: 'Rodríguez',
                        apellido_materno: 'Hernández',
                        fecha_nacimiento: '1990-08-22',
                        telefono: '5559876543',
                        email: 'maria.rodriguez@email.com',
                        found: true
                    }
                };
                
                const record = mockMedicalData[expedienteNumber.toUpperCase()];
                
                if (record && record.found) {
                    // Auto-fill form with found data
                    fillFormWithMedicalData(record);
                    
                    // Show success message
                    showMedicalRecordMessage('success', `
                        <i class="fas fa-check-circle"></i> 
                        Expediente médico encontrado. Los datos han sido cargados automáticamente.
                    `);
                } else {
                    // Show not found message
                    showMedicalRecordMessage('warning', `
                        <i class="fas fa-exclamation-triangle"></i> 
                        No se encontró información para el expediente "${expedienteNumber}". Puede continuar con el registro manual.
                    `);
                }
            }, 1500); // Simulate network delay
        }
        
        // Function to fill form with medical record data
        function fillFormWithMedicalData(data) {
            try {
                // Fill basic information
                if (data.nombre) {
                    const nameField = document.querySelector('input[name="nombre"]');
                    if (nameField && !nameField.value) nameField.value = data.nombre;
                }
                
                if (data.apellido_paterno) {
                    const paternalField = document.querySelector('input[name="apellido_paterno"]');
                    if (paternalField && !paternalField.value) paternalField.value = data.apellido_paterno;
                }
                
                if (data.apellido_materno) {
                    const maternalField = document.querySelector('input[name="apellido_materno"]');
                    if (maternalField && !maternalField.value) maternalField.value = data.apellido_materno;
                }
                
                if (data.fecha_nacimiento) {
                    const birthdateField = document.querySelector('input[name="fecha_nacimiento"]');
                    if (birthdateField && !birthdateField.value) birthdateField.value = data.fecha_nacimiento;
                }
                
                if (data.telefono) {
                    const phoneField = document.querySelector('input[name="telefono"]');
                    if (phoneField && !phoneField.value) phoneField.value = data.telefono;
                }
                
                if (data.email) {
                    const emailField = document.querySelector('input[name="email"]');
                    if (emailField && !emailField.value) emailField.value = data.email;
                }
                
                console.log('📋 Medical record data loaded successfully');
                
                // Log this as an audit event
                console.log('🔍 Medical record search completed for expediente:', data);
                
            } catch (error) {
                console.error('❌ Error filling form with medical data:', error);
                showMedicalRecordMessage('error', '<i class="fas fa-times-circle"></i> Error al cargar los datos. Por favor, ingrese la información manualmente.');
            }
        }
        
        // Function to show medical record search messages
        function showMedicalRecordMessage(type, message) {
            const expedienteInput = document.querySelector('input[name="expediente_medico"]');
            const parentDiv = expedienteInput.closest('.field-group');
            
            // Remove existing message
            const existingMessage = parentDiv.querySelector('.medical-record-message');
            if (existingMessage) {
                existingMessage.remove();
            }
            
            // Create new message
            const messageDiv = document.createElement('div');
            messageDiv.className = 'medical-record-message';
            messageDiv.style.cssText = `
                margin-top: 0.5rem; 
                padding: 0.75rem; 
                border-radius: 6px; 
                font-size: 0.875rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            `;
            
            // Set message style based on type
            switch (type) {
                case 'success':
                    messageDiv.style.background = '#f0fdf4';
                    messageDiv.style.border = '1px solid #22c55e';
                    messageDiv.style.color = '#166534';
                    break;
                case 'warning':
                    messageDiv.style.background = '#fffbeb';
                    messageDiv.style.border = '1px solid #f59e0b';
                    messageDiv.style.color = '#92400e';
                    break;
                case 'error':
                    messageDiv.style.background = '#fef2f2';
                    messageDiv.style.border = '1px solid #ef4444';
                    messageDiv.style.color = '#dc2626';
                    break;
            }
            
            messageDiv.innerHTML = message;
            parentDiv.appendChild(messageDiv);
            
            // Auto-remove message after 8 seconds
            setTimeout(() => {
                if (messageDiv.parentNode) {
                    messageDiv.parentNode.removeChild(messageDiv);
                }
            }, 8000);
        }

        // Function to simulate face verification for testing
        function simulateVerification() {
            console.log('🧪 Simulating face verification for testing...');
            
            // Show verified status
            document.getElementById('verificationPending').style.display = 'none';
            document.getElementById('verificationComplete').style.display = 'block';
            
            // Set confidence to 95%
            document.getElementById('verificationConfidence').textContent = '95';
            
            // Enable final submit button
            const submitBtn = document.getElementById('finalSubmitBtn');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-check-circle"></i> Completar Registro';
            submitBtn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
            
            // Store verification status in sessionStorage
            sessionStorage.setItem('faceVerificationComplete', 'true');
            sessionStorage.setItem('faceVerificationConfidence', '95');
            
            // Update hidden form inputs
            document.getElementById('faceVerifiedInput').value = 'true';
            document.getElementById('faceConfidenceInput').value = '95';
            
            console.log('✅ Face verification simulated - registration can proceed');
            
            // Show success message
            const message = document.createElement('div');
            message.style.cssText = 'background: #f0fdf4; border: 1px solid #22c55e; border-radius: 8px; padding: 1rem; margin: 1rem 0; color: #166534; text-align: center;';
            message.innerHTML = `
                <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-weight: 600;">
                    <i class="fas fa-check-circle"></i>
                    Verificación Facial Simulada (Test Mode)
                </div>
                <p style="font-size: 0.9rem; margin: 0.5rem 0 0 0;">Ahora puede completar el registro usando el botón "Completar Registro".</p>
            `;
            
            const verificationSection = document.getElementById('faceVerificationStatus');
            verificationSection.appendChild(message);
            
            // Remove message after 5 seconds
            setTimeout(() => {
                if (message.parentNode) {
                    message.parentNode.removeChild(message);
                }
            }, 5000);
        }

        // Function to save form data before face verification
        function saveFormDataAndRedirect() {
            console.log('💾 Saving form data before face verification...');
            
            try {
                const formData = collectFormData();
                sessionStorage.setItem('registrationFormData', JSON.stringify(formData));
                console.log('✅ Form data saved successfully:', formData);
                
                // Redirect to face verification with return URL
                window.location.href = '/face-verification?return_to=' + encodeURIComponent(window.location.href);
                
            } catch (error) {
                console.error('❌ Error saving form data:', error);
                // Still allow redirect even if save fails
                window.location.href = '/face-verification';
            }
        }

        // Function to collect all form data
        function collectFormData() {
            const form = document.getElementById('registryForm');
            const formData = {};
            
            // Get all form inputs
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (input.name && input.value) {
                    if (input.type === 'radio') {
                        if (input.checked) {
                            formData[input.name] = input.value;
                        }
                    } else if (input.type === 'checkbox') {
                        formData[input.name] = input.checked;
                    } else {
                        formData[input.name] = input.value;
                    }
                }
            });
            
            console.log('📋 Collected form data fields:', Object.keys(formData));
            return formData;
        }

        // Function to restore form data
        function restoreFormData() {
            console.log('🔄 Attempting to restore form data...');
            
            try {
                const savedData = sessionStorage.getItem('registrationFormData');
                if (!savedData) {
                    console.log('ℹ️ No saved form data found');
                    return false;
                }
                
                const formData = JSON.parse(savedData);
                console.log('📥 Found saved form data:', formData);
                
                // Restore each field
                Object.keys(formData).forEach(fieldName => {
                    const value = formData[fieldName];
                    const field = document.querySelector(`[name="${fieldName}"]`);
                    
                    if (field) {
                        if (field.type === 'radio') {
                            const radioOption = document.querySelector(`[name="${fieldName}"][value="${value}"]`);
                            if (radioOption) {
                                radioOption.checked = true;
                                console.log(`✅ Restored radio field: ${fieldName} = ${value}`);
                            }
                        } else if (field.type === 'checkbox') {
                            field.checked = value;
                            console.log(`✅ Restored checkbox field: ${fieldName} = ${value}`);
                        } else {
                            field.value = value;
                            console.log(`✅ Restored field: ${fieldName} = ${value}`);
                            
                            // Trigger validation for special fields
                            if (fieldName === 'curp') {
                                field.dispatchEvent(new Event('input'));
                            } else if (fieldName === 'password') {
                                field.dispatchEvent(new Event('input'));
                            } else if (fieldName === 'password_confirmation') {
                                field.dispatchEvent(new Event('input'));
                            } else if (fieldName === 'estado') {
                                // Trigger state change to update municipalities
                                updateMunicipalities();
                            }
                        }
                    } else {
                        console.log(`⚠️ Field not found: ${fieldName}`);
                    }
                });
                
                console.log('✅ Form data restoration completed');
                
                // Trigger medical record field visibility check after restoration
                toggleMedicalRecordField();
                
                // Show success message
                showFormDataRestoredMessage();
                
                return true;
                
            } catch (error) {
                console.error('❌ Error restoring form data:', error);
                return false;
            }
        }

        // Function to show form data restored message
        function showFormDataRestoredMessage() {
            const message = document.createElement('div');
            message.style.cssText = 'background: #f0fdf4; border: 1px solid #22c55e; border-radius: 8px; padding: 1rem; margin: 1rem 0; color: #166534; text-align: center;';
            message.innerHTML = `
                <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-weight: 600;">
                    <i class="fas fa-check-circle"></i>
                    Datos del formulario restaurados
                </div>
                <p style="font-size: 0.9rem; margin: 0.5rem 0 0 0;">Sus datos anteriores han sido recuperados automáticamente.</p>
            `;
            
            // Insert message at the top of the form
            const form = document.getElementById('registryForm');
            if (form) {
                form.insertBefore(message, form.firstChild);
                
                // Remove message after 5 seconds
                setTimeout(() => {
                    if (message.parentNode) {
                        message.parentNode.removeChild(message);
                    }
                }, 5000);
            }
        }

        // Function to clear saved form data
        function clearSavedFormData() {
            try {
                sessionStorage.removeItem('registrationFormData');
                sessionStorage.removeItem('faceVerificationComplete');
                sessionStorage.removeItem('faceVerificationConfidence');
                console.log('🗑️ Cleared saved form data and verification status');
            } catch (error) {
                console.error('❌ Error clearing saved form data:', error);
            }
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
            
            // Helper function to make field read-only with visual indicators
            function makeFieldReadOnly(input, value, fieldName) {
                if (input && value && !input.value) {
                    input.value = value;
                    input.readOnly = true;
                    input.style.backgroundColor = '#f0fdf4';
                    input.style.borderColor = '#10B981';
                    input.style.color = '#065f46';
                    input.style.fontWeight = '600';
                    input.setAttribute('data-curp-verified', 'true');
                    
                    // Add verification icon
                    const container = input.parentElement;
                    if (container && !container.querySelector('.curp-verified-icon')) {
                        const icon = document.createElement('span');
                        icon.className = 'curp-verified-icon';
                        icon.innerHTML = '<i class="fas fa-shield-check" style="color: #10B981; margin-left: 8px; font-size: 0.9rem;"></i>';
                        icon.title = 'Dato verificado con CURP - No modificable';
                        container.style.position = 'relative';
                        container.appendChild(icon);
                        
                        // Position icon
                        icon.style.position = 'absolute';
                        icon.style.right = '10px';
                        icon.style.top = '50%';
                        icon.style.transform = 'translateY(-50%)';
                        icon.style.pointerEvents = 'none';
                    }
                    
                    console.log(`✅ ${fieldName} filled and locked:`, value);
                    return true;
                }
                return false;
            }
            
            // Helper function for select elements
            function makeSelectReadOnly(select, value, fieldName) {
                if (select && value && !select.value) {
                    select.value = value;
                    // Use pointer-events: none instead of disabled to ensure form submission includes the value
                    select.style.pointerEvents = 'none';
                    select.style.backgroundColor = '#f0fdf4';
                    select.style.borderColor = '#10B981';
                    select.style.color = '#065f46';
                    select.style.fontWeight = '600';
                    select.setAttribute('data-curp-verified', 'true');
                    select.setAttribute('readonly', 'true');
                    
                    // Add verification icon for select
                    const container = select.parentElement;
                    if (container && !container.querySelector('.curp-verified-icon')) {
                        const icon = document.createElement('span');
                        icon.className = 'curp-verified-icon';
                        icon.innerHTML = '<i class="fas fa-shield-check" style="color: #10B981; margin-left: 8px; font-size: 0.9rem;"></i>';
                        icon.title = 'Dato verificado con CURP - No modificable';
                        container.style.position = 'relative';
                        container.appendChild(icon);
                        
                        // Position icon
                        icon.style.position = 'absolute';
                        icon.style.right = '30px';
                        icon.style.top = '50%';
                        icon.style.transform = 'translateY(-50%)';
                        icon.style.pointerEvents = 'none';
                    }
                    
                    console.log(`✅ ${fieldName} filled and locked:`, value);
                    return true;
                }
                return false;
            }
            
            // Fill and lock CURP
            const curpInput = document.querySelector('input[name="curp"]');
            makeFieldReadOnly(curpInput, data.curp, 'CURP');
            
            // Fill and lock name fields if available
            if (details.nombres) {
                const nombreInput = document.querySelector('input[name="nombres"]');
                makeFieldReadOnly(nombreInput, details.nombres, 'Nombres');
            }
            
            if (details.primerApellido) {
                const apellidoPaternoInput = document.querySelector('input[name="apellido_paterno"]');
                makeFieldReadOnly(apellidoPaternoInput, details.primerApellido, 'Apellido Paterno');
            }
            
            if (details.segundoApellido) {
                const apellidoMaternoInput = document.querySelector('input[name="apellido_materno"]');
                makeFieldReadOnly(apellidoMaternoInput, details.segundoApellido, 'Apellido Materno');
            }
            
            // Fill and lock birth date if available
            if (details.fechaNacimiento) {
                const fechaNacimientoInput = document.querySelector('input[name="fecha_nacimiento"]');
                makeFieldReadOnly(fechaNacimientoInput, details.fechaNacimiento, 'Fecha de Nacimiento');
            }
            
            // Fill and lock gender if available
            if (details.sexo) {
                const sexoSelect = document.querySelector('select[name="sexo"]');
                const sexoValue = details.sexo.toLowerCase().includes('masculino') ? 'masculino' : 
                                 details.sexo.toLowerCase().includes('femenino') ? 'femenino' : '';
                if (sexoValue) {
                    makeSelectReadOnly(sexoSelect, sexoValue, 'Sexo');
                }
            }
            
            // Fill and lock state of birth if available
            if (details.entidadNacimiento) {
                const estadoNacimientoInput = document.querySelector('input[name="estado_nacimiento"]');
                makeFieldReadOnly(estadoNacimientoInput, details.entidadNacimiento, 'Estado de Nacimiento');
            }
            
            // Show verification notification
            showCurpVerificationNotification();
            
            console.log('📝 Auto-fill process completed with read-only fields');
        }
        
        // Function to show CURP verification notification
        function showCurpVerificationNotification() {
            const existingNotification = document.querySelector('.curp-verification-notification');
            if (existingNotification) {
                existingNotification.remove();
            }
            
            const notification = document.createElement('div');
            notification.className = 'curp-verification-notification';
            notification.style.cssText = `
                background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
                border: 1px solid #10B981;
                border-radius: 12px;
                padding: 1rem 1.5rem;
                margin: 1rem 0;
                color: #065f46;
                font-size: 0.9rem;
                box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.1);
            `;
            notification.innerHTML = `
                <div style="display: flex; align-items: flex-start; gap: 1rem;">
                    <div style="flex-shrink: 0;">
                        <i class="fas fa-shield-check" style="color: #10B981; font-size: 1.2rem; margin-top: 2px;"></i>
                    </div>
                    <div style="flex-grow: 1;">
                        <div style="font-weight: 600; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                            Datos Verificados con CURP
                            <span style="background: #10B981; color: white; padding: 0.1rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 500;">OFICIAL</span>
                        </div>
                        <p style="margin: 0; line-height: 1.4;">
                            Los campos marcados con <i class="fas fa-shield-check" style="color: #10B981; font-size: 0.8rem;"></i> 
                            han sido verificados contra la base de datos oficial de RENAPO y no pueden modificarse. 
                            Complete los campos restantes para finalizar su registro.
                        </p>
                    </div>
                </div>
            `;
            
            // Insert notification at the top of the form
            const form = document.querySelector('.registration-form');
            if (form) {
                form.insertBefore(notification, form.firstChild);
            }
        }
        
        // Function to validate CURP-verified fields haven't been tampered with
        function validateCurpVerifiedFields(event) {
            const curpVerifiedFields = document.querySelectorAll('[data-curp-verified="true"]');
            if (curpVerifiedFields.length === 0) {
                return true; // No CURP-verified fields to validate
            }
            
            let hasInvalidField = false;
            const invalidFields = [];
            
            curpVerifiedFields.forEach(field => {
                const fieldName = field.name || field.getAttribute('name');
                
                // Check if field is still marked as read-only
                if (field.tagName.toLowerCase() === 'select') {
                    if (field.style.pointerEvents !== 'none' || !field.hasAttribute('readonly')) {
                        hasInvalidField = true;
                        invalidFields.push(fieldName);
                    }
                } else {
                    if (!field.readOnly) {
                        hasInvalidField = true;
                        invalidFields.push(fieldName);
                    }
                }
                
                // Check if field styling has been removed
                if (field.style.backgroundColor !== 'rgb(240, 253, 244)') {
                    hasInvalidField = true;
                    invalidFields.push(fieldName);
                }
            });
            
            if (hasInvalidField) {
                event.preventDefault();
                
                // Show security alert
                const alertDiv = document.createElement('div');
                alertDiv.style.cssText = `
                    position: fixed;
                    top: 20px;
                    left: 50%;
                    transform: translateX(-50%);
                    background: #fef2f2;
                    border: 1px solid #fecaca;
                    border-radius: 8px;
                    padding: 1rem 1.5rem;
                    color: #991b1b;
                    z-index: 10000;
                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
                    max-width: 400px;
                    text-align: center;
                `;
                alertDiv.innerHTML = `
                    <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-weight: 600; margin-bottom: 0.5rem;">
                        <i class="fas fa-shield-slash"></i>
                        Error de Seguridad
                    </div>
                    <p style="margin: 0; font-size: 0.9rem;">
                        Los campos verificados con CURP han sido modificados. Por favor, recargue la página e inicie el proceso nuevamente.
                    </p>
                    <button onclick="this.parentElement.remove(); window.location.reload();" 
                            style="margin-top: 1rem; background: #dc2626; color: white; border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; font-size: 0.9rem;">
                        Recargar Página
                    </button>
                `;
                document.body.appendChild(alertDiv);
                
                // Remove alert after 10 seconds
                setTimeout(() => {
                    if (alertDiv && alertDiv.parentElement) {
                        alertDiv.remove();
                    }
                }, 10000);
                
                console.error('❌ CURP verification security check failed. Modified fields:', invalidFields);
                return false;
            }
            
            return true;
        }

        // Function to handle registration method selection
        function selectRegistrationMethod(method) {
            const methodsSection = document.getElementById('registrationMethods');
            const form = document.getElementById('registryForm');
            
            // Log registration started
            logRegistrationStarted(method);
            
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
        
        // Function to log registration started
        function logRegistrationStarted(method) {
            try {
                // This would normally make an AJAX call to log the event
                // For demonstration purposes, we'll log to console
                console.log('📝 Registration started:', {
                    method: method,
                    timestamp: new Date().toISOString(),
                    session: 'sess_' + Math.random().toString(36).substr(2, 9)
                });
                
                // In a real implementation, you would make an API call like:
                // fetch('/api/log-audit-event', {
                //     method: 'POST',
                //     headers: {
                //         'Content-Type': 'application/json',
                //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                //     },
                //     body: JSON.stringify({
                //         event_type: 'registration_started',
                //         status: 'in_progress',
                //         event_data: { registration_method: method }
                //     })
                // });
            } catch (error) {
                console.warn('Failed to log registration started:', error);
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
            
            // Also check sessionStorage for persistent verification status
            const storedVerification = sessionStorage.getItem('faceVerificationComplete');
            const storedConfidence = sessionStorage.getItem('faceVerificationConfidence');
            
            console.log('🔍 Checking face verification status:', { 
                faceVerified, 
                confidence, 
                storedVerification, 
                storedConfidence 
            });
            
            if (faceVerified === 'true' || storedVerification === 'true') {
                // Show verified status
                document.getElementById('verificationPending').style.display = 'none';
                document.getElementById('verificationComplete').style.display = 'block';
                
                const displayConfidence = confidence || storedConfidence;
                if (displayConfidence) {
                    document.getElementById('verificationConfidence').textContent = displayConfidence;
                }
                
                // Enable final submit button
                const submitBtn = document.getElementById('finalSubmitBtn');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check-circle"></i> Completar Registro';
                submitBtn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                
                // Store verification status in sessionStorage (only if from URL)
                if (faceVerified === 'true') {
                    sessionStorage.setItem('faceVerificationComplete', 'true');
                    if (confidence) {
                        sessionStorage.setItem('faceVerificationConfidence', confidence);
                    }
                }
                
                // Update hidden form inputs
                document.getElementById('faceVerifiedInput').value = 'true';
                const finalConfidence = confidence || storedConfidence;
                if (finalConfidence) {
                    document.getElementById('faceConfidenceInput').value = finalConfidence;
                }
                
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
            // Check if face verification is complete by looking at the button state
            const submitBtn = document.getElementById('finalSubmitBtn');
            const isVerificationComplete = submitBtn && !submitBtn.disabled && 
                (submitBtn.innerHTML.includes('Completar Registro') || submitBtn.innerHTML.includes('check-circle'));
            
            // Also check URL parameters and sessionStorage as backup
            const urlParams = new URLSearchParams(window.location.search);
            const faceVerified = urlParams.get('face_verified');
            const storedVerification = sessionStorage.getItem('faceVerificationComplete');
            
            // Check if face verification is complete
            console.log('🔍 Form validation check:', {
                isVerificationComplete,
                faceVerified,
                storedVerification,
                submitBtnHtml: submitBtn ? submitBtn.innerHTML : 'not found'
            });
            
            if (!isVerificationComplete && faceVerified !== 'true' && storedVerification !== 'true') {
                console.log('❌ Form submission prevented - face verification not complete');
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
            
            console.log('✅ Form validation passed - proceeding with submission');
            return true;
        }

        // Auto-save form data periodically
        function setupAutoSave() {
            const form = document.getElementById('registryForm');
            if (!form) return;
            
            // Save form data when user types in any field
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('input', debounce(autoSaveFormData, 1000));
                input.addEventListener('change', autoSaveFormData);
            });
            
            console.log('🔄 Auto-save functionality enabled');
        }
        
        // Auto-save function with debouncing
        function autoSaveFormData() {
            try {
                const formData = collectFormData();
                if (Object.keys(formData).length > 0) {
                    sessionStorage.setItem('registrationFormData', JSON.stringify(formData));
                    console.log('💾 Auto-saved form data');
                }
            } catch (error) {
                console.error('❌ Error auto-saving form data:', error);
            }
        }
        
        // Debounce function to limit auto-save frequency
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // ALWAYS skip method selection and show form directly
        function checkDirectRegistration() {
            console.log('📝 Always showing registration form directly - method selection disabled');
            
            // ALWAYS hide method selection and show form (with null checks)
            const methodsElement = document.getElementById('registrationMethods');
            if (methodsElement) {
                methodsElement.style.display = 'none';
            }
            
            const formElement = document.getElementById('registryForm');
            if (formElement) {
                formElement.style.display = 'block';
            }
            
            // Update page title to indicate direct registration
            const pageTitle = document.querySelector('.page-title');
            if (pageTitle) {
                pageTitle.textContent = 'Crear Nueva Cuenta';
            }
            
            const pageSubtitle = document.querySelector('.page-subtitle');
            if (pageSubtitle) {
                pageSubtitle.textContent = 'Complete el formulario para registrarse';
            }
            
            // Update progress bar to show we're already in the form step
            const progressFill = document.querySelector('.progress-fill');
            if (progressFill) {
                progressFill.style.width = '60%';
            }
            
            return true;
        }

        // Initialize handlers when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Check if this is direct registration first
            const isDirect = checkDirectRegistration();
            
            // First check for returns from other pages
            checkCurpReturn();
            handleCurpValidationReturn();
            
            // Initialize form functionality
            initializeStateMunicipalityRelationship();
            checkFaceVerificationStatus();
            
            // Try to restore saved form data
            const restored = restoreFormData();
            if (!restored) {
                console.log('ℹ️ No previous form data to restore');
            }
            
            // Setup auto-save after restoration
            setupAutoSave();
            
            // Add form submission validation and cleanup
            const form = document.getElementById('registryForm');
            if (form) {
                form.addEventListener('submit', function(event) {
                    // Validate CURP-verified fields haven't been tampered with
                    if (!validateCurpVerifiedFields(event)) {
                        return false;
                    }
                    
                    const isValid = validateRegistrationForm(event);
                    if (isValid) {
                        // Clear saved data on successful submission
                        clearSavedFormData();
                    }
                });
            }
            
            // If direct registration, scroll to form
            if (isDirect) {
                setTimeout(() => {
                    document.getElementById('registryForm').scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'start' 
                    });
                }, 100);
            }
        });
    </script>
</body>
</html>