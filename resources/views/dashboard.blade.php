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
            background: rgba(15, 76, 117, 0.2);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(15, 76, 117, 0.4);
        }

        /* Firefox Scrollbar */
        html {
            scrollbar-width: thin;
            scrollbar-color: rgba(15, 76, 117, 0.2) rgba(0,0,0,0.05);
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

        /* Header */
        .main-header {
            background: linear-gradient(135deg, #0F4C75 0%, #3282B8 100%);
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
            background: linear-gradient(90deg, #0F4C75, #3282B8, #0F4C75);
        }

        .dashboard-title h1 {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #0F4C75, #3282B8);
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
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 24px;
            padding: 0;
            box-shadow: 0 25px 60px rgba(0,0,0,0.08);
            border: 1px solid rgba(255,255,255,0.6);
            position: relative;
            overflow: hidden;
            margin-top: 2rem;
        }

        .contact-header {
            background: linear-gradient(135deg, #0F4C75 0%, #3282B8 100%);
            padding: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .contact-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="contact-pattern" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23contact-pattern)"/></svg>');
            pointer-events: none;
        }

        .contact-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .contact-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .contact-content {
            padding: 2.5rem;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            max-width: 1000px;
            margin: 0 auto;
        }

        .contact-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.06);
            border: 1px solid rgba(139, 21, 56, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .contact-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #0F4C75, #3282B8);
        }

        .contact-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
            border-color: rgba(139, 21, 56, 0.2);
        }

        .contact-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1rem;
        }

        .contact-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #0F4C75, #3282B8);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(139, 21, 56, 0.3);
        }

        .contact-label {
            font-weight: 700;
            color: #1f2937;
            font-size: 1.1rem;
            margin: 0;
        }

        .contact-value {
            color: #4b5563;
            font-size: 0.95rem;
            line-height: 1.6;
            margin: 0;
            padding-left: 12px;
        }

        .contact-value a {
            color: #0F4C75;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .contact-value a:hover {
            color: #3282B8;
            text-decoration: none;
        }

        .contact-value strong {
            color: #1f2937;
            font-weight: 600;
        }

        /* Special styling for different contact types */
        .contact-card.email .contact-icon {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .contact-card.phone .contact-icon {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .contact-card.address .contact-icon {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .contact-card.schedule .contact-icon {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        }

        /* Enhanced hover effects for contact links */
        .contact-card.email:hover {
            background: linear-gradient(135deg, #ffffff, #eff6ff);
        }

        .contact-card.phone:hover {
            background: linear-gradient(135deg, #ffffff, #f0fdf4);
        }

        .contact-card.address:hover {
            background: linear-gradient(135deg, #ffffff, #fffbeb);
        }

        .contact-card.schedule:hover {
            background: linear-gradient(135deg, #ffffff, #faf5ff);
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
        @media (max-width: 1024px) {
            .contact-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.2rem;
            }
        }

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
                gap: 1.2rem;
            }

            .contact-header {
                padding: 1.5rem;
                text-align: center;
            }

            .contact-title {
                font-size: 1.5rem;
            }

            .contact-content {
                padding: 1.5rem;
            }

            .contact-card {
                padding: 1.2rem;
            }

            .contact-icon {
                width: 40px;
                height: 40px;
                font-size: 1rem;
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
                    
                    <!-- Gold Accents -->
                    <circle cx="60" cy="25" r="2" fill="#3282B8"/>
                    <rect x="58" y="78" width="4" height="4" fill="#3282B8" rx="1"/>
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
            <div class="contact-header">
                <h2 class="contact-title">
                    <i class="fas fa-address-book"></i>
                    Información de Contacto
                </h2>
                <p class="contact-subtitle">Mantente en contacto con nosotros para cualquier consulta o soporte</p>
            </div>
            
            <div class="contact-content">
                <div class="contact-grid">
                    <div class="contact-card email">
                        <div class="contact-card-header">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-label">Correo Electrónico</div>
                        </div>
                        <div class="contact-value">
                            <a href="mailto:dgmm.enlace.informatico@semar.gob.mx">dgmm.enlace.informatico@semar.gob.mx</a>
                            <br><small style="color: #9ca3af;">Respuesta en 24-48 horas</small>
                        </div>
                    </div>

                    <div class="contact-card phone">
                        <div class="contact-card-header">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-label">Atención Telefónica</div>
                        </div>
                        <div class="contact-value">
                            <a href="tel:+525556246200">(55) 56246200 <strong>Ext. 6079</strong></a>
                            <br><small style="color: #9ca3af;">Línea directa de soporte</small>
                        </div>
                    </div>

                    <div class="contact-card address">
                        <div class="contact-card-header">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-label">Ubicación</div>
                        </div>
                        <div class="contact-value">
                            <strong>Heroica Escuela Naval Militar No. 669</strong><br>
                            1a sección, Alcaldía Coyoacán<br>
                            <strong>C.P. 04470, Ciudad de México</strong>
                        </div>
                    </div>

                    <div class="contact-card schedule">
                        <div class="contact-card-header">
                            <div class="contact-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="contact-label">Horario de Atención</div>
                        </div>
                        <div class="contact-value">
                            <strong>8:30 AM - 4:00 PM</strong><br>
                            Lunes a Viernes<br>
                            <small style="color: #9ca3af;">Excepto días festivos</small>
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