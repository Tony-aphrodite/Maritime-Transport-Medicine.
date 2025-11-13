<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MARINA Portal</title>
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
        }

        /* Header */
        .main-header {
            background: linear-gradient(135deg, #8B1538 0%, #a91d42 100%);
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 8px 32px rgba(139, 21, 56, 0.3);
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

        .user-info {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 4px;
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.1);
            padding: 12px 20px;
            border-radius: 12px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .user-name {
            font-weight: 700;
            font-size: 1.1rem;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }

        .user-curp {
            font-size: 0.9rem;
            opacity: 0.9;
            font-weight: 500;
        }

        /* Main Content */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .dashboard-title {
            text-align: center;
            margin-bottom: 2rem;
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.3);
        }

        .dashboard-title::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #8B1538, #D4AF37, #8B1538);
        }

        .dashboard-title h1 {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #8B1538, #a91d42);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .dashboard-subtitle {
            color: #64748b;
            font-size: 1rem;
            font-weight: 500;
        }

        /* Warning Message */
        .warning-section {
            margin-bottom: 2rem;
        }

        .warning-box {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border: 2px solid #f59e0b;
            border-radius: 12px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.2);
        }

        .warning-box i {
            font-size: 1.5rem;
            color: #d97706;
        }

        .warning-text {
            font-weight: 600;
            color: #92400e;
            font-size: 1.1rem;
        }

        /* Main Action Buttons */
        .action-buttons {
            margin-bottom: 3rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .action-btn {
            background: white;
            border: none;
            border-radius: 16px;
            padding: 2.5rem 2rem;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            border: 1px solid rgba(255,255,255,0.3);
        }

        .action-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .btn-schedule {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .btn-schedule:hover {
            background: linear-gradient(135deg, #059669, #047857);
        }

        .btn-check {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .btn-check:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
        }

        .action-btn i {
            font-size: 3rem;
            opacity: 0.9;
        }

        .action-btn-title {
            font-size: 1.3rem;
            font-weight: 700;
            text-align: center;
        }

        .action-btn-subtitle {
            font-size: 0.9rem;
            opacity: 0.8;
            text-align: center;
        }

        /* Contact Section */
        .contact-section {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.3);
            position: relative;
            overflow: hidden;
        }

        .contact-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #8B1538, #D4AF37, #8B1538);
        }

        .contact-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #8B1538;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 12px;
            border-left: 4px solid #8B1538;
        }

        .contact-item i {
            color: #8B1538;
            font-size: 1.2rem;
            margin-top: 2px;
            min-width: 20px;
        }

        .contact-content {
            flex: 1;
        }

        .contact-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.3rem;
        }

        .contact-value {
            color: #6b7280;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .contact-value a {
            color: #8B1538;
            text-decoration: none;
        }

        .contact-value a:hover {
            text-decoration: underline;
        }

        /* Logout Button */
        .logout-section {
            margin-top: 2rem;
            text-align: center;
        }

        .logout-btn {
            background: linear-gradient(135deg, #64748b, #475569);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, #475569, #334155);
            transform: translateY(-2px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-header {
                flex-direction: column;
                gap: 1.5rem;
                text-align: center;
                padding: 2rem 1rem;
            }

            .user-info {
                align-items: center;
            }

            .container {
                padding: 1rem;
            }

            .action-buttons {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .contact-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .dashboard-title {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .dashboard-title h1 {
                font-size: 1.5rem;
            }

            .action-btn {
                padding: 2rem 1.5rem;
            }

            .action-btn i {
                font-size: 2.5rem;
            }

            .action-btn-title {
                font-size: 1.1rem;
            }
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
                          fill="#8B1538" stroke="#D4AF37" stroke-width="2"/>
                    
                    <!-- Inner Shield -->
                    <path d="M60 15 L80 23 L80 48 Q80 70 60 90 Q40 70 40 48 L40 23 Z" 
                          fill="white" opacity="0.95"/>
                    
                    <!-- Medical Cross -->
                    <rect x="57" y="35" width="6" height="20" fill="#8B1538"/>
                    <rect x="50" y="42" width="20" height="6" fill="#8B1538"/>
                    
                    <!-- Digital Circuit Lines -->
                    <g stroke="#D4AF37" stroke-width="1.5" fill="none" opacity="0.8">
                        <path d="M45 30 L50 30 L52 32 L55 32"/>
                        <path d="M65 32 L68 32 L70 30 L75 30"/>
                        <path d="M45 60 L48 60 L50 58 L53 58"/>
                        <path d="M67 58 L70 58 L72 60 L75 60"/>
                        <circle cx="47" cy="30" r="1.5" fill="#D4AF37"/>
                        <circle cx="73" cy="30" r="1.5" fill="#D4AF37"/>
                        <circle cx="47" cy="60" r="1.5" fill="#D4AF37"/>
                        <circle cx="73" cy="60" r="1.5" fill="#D4AF37"/>
                    </g>
                    
                    <!-- Maritime Wave -->
                    <path d="M40 70 Q50 65 60 70 T80 70" 
                          stroke="#2C3E50" stroke-width="2" fill="none"/>
                    <path d="M42 75 Q52 70 62 75 T82 75" 
                          stroke="#2C3E50" stroke-width="1.5" fill="none" opacity="0.6"/>
                    
                    <!-- Gold Accents -->
                    <circle cx="60" cy="25" r="2" fill="#D4AF37"/>
                    <rect x="58" y="78" width="4" height="4" fill="#D4AF37" rx="1"/>
                </svg>
            </div>
            <div class="header-title">MARINA - Secretaría de Marina</div>
        </div>
        <div class="user-info">
            <div class="user-name">Juan Carlos Pérez González</div>
            <div class="user-curp">CURP: PEGJ850415HDFRRN05</div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="dashboard-title">
            <h1>Panel de Control</h1>
            <p class="dashboard-subtitle">Sistema Digital de Certificación Médica</p>
        </div>

        <!-- Warning Message -->
        <div class="warning-section">
            <div class="warning-box">
                <i class="fas fa-exclamation-triangle"></i>
                <div class="warning-text">Solo se permite agendar 3 citas por año.</div>
            </div>
        </div>

        <!-- Main Action Buttons -->
        <div class="action-buttons">
            <a href="#agendar-cita" class="action-btn btn-schedule">
                <i class="fas fa-calendar-plus"></i>
                <div class="action-btn-title">Agendar Cita</div>
                <div class="action-btn-subtitle">Programar nueva cita médica</div>
            </a>

            <a href="#consulta-citas" class="action-btn btn-check">
                <i class="fas fa-calendar-check"></i>
                <div class="action-btn-title">Consulta Citas</div>
                <div class="action-btn-subtitle">Ver citas programadas</div>
            </a>
        </div>

        <!-- Contact Section -->
        <div class="contact-section">
            <h2 class="contact-title">
                <i class="fas fa-address-book"></i>
                Información de Contacto
            </h2>
            
            <div class="contact-grid">
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <div class="contact-content">
                        <div class="contact-label">Correo Electrónico</div>
                        <div class="contact-value">
                            <a href="mailto:dgmm.enlace.informatico@semar.gob.mx">dgmm.enlace.informatico@semar.gob.mx</a>
                        </div>
                    </div>
                </div>

                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <div class="contact-content">
                        <div class="contact-label">Teléfono</div>
                        <div class="contact-value">
                            <a href="tel:+525556246200">(55) 56246200 Ext. 6079</a>
                        </div>
                    </div>
                </div>

                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div class="contact-content">
                        <div class="contact-label">Dirección</div>
                        <div class="contact-value">
                            Heroica Escuela Naval Militar No. 669, 1a sección,<br>
                            Alcaldía Coyoacán, C.P. 04470, Ciudad de México
                        </div>
                    </div>
                </div>

                <div class="contact-item">
                    <i class="fas fa-clock"></i>
                    <div class="contact-content">
                        <div class="contact-label">Horario de Atención</div>
                        <div class="contact-value">
                            8:30–16:00<br>
                            Lunes a Viernes
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logout Section -->
        <div class="logout-section">
            <a href="/login" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                Cerrar Sesión
            </a>
        </div>
    </div>
</body>
</html>