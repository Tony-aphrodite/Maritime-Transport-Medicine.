<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MARINA - Portal de Acceso</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f6fa;
            color: #333;
            line-height: 1.6;
        }

        /* Header */
        .header {
            background-color: #8B1538;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo svg {
            width: 100%;
            height: 100%;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }

        .title {
            font-size: 1.4rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .nav-links {
            display: flex;
            gap: 30px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .nav-links a:hover {
            background-color: rgba(255,255,255,0.1);
        }

        /* Main Content */
        .main-content {
            min-height: calc(100vh - 80px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }

        .login-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            border: 1px solid #e1e8ed;
        }

        .logo-branding {
            text-align: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #e1e8ed;
        }

        .logo-branding .logo-container {
            margin-bottom: 1rem;
        }

        .logo-branding svg {
            width: 80px;
            height: 80px;
        }

        .branding-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #8B1538;
            margin-bottom: 0.3rem;
            letter-spacing: 0.5px;
        }

        .branding-subtitle {
            font-size: 0.9rem;
            color: #7f8c8d;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .welcome-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .input-container {
            position: relative;
        }

        .input-container i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #7f8c8d;
            font-size: 1.1rem;
        }

        .form-input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #fafbfc;
        }

        .form-input:focus {
            outline: none;
            border-color: #8B1538;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(139, 21, 56, 0.1);
        }

        .form-input::placeholder {
            color: #95a5a6;
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            background-color: #8B1538;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }

        .login-btn:hover {
            background-color: #6d1029;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(139, 21, 56, 0.3);
        }

        .form-links {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
        }

        .form-links a {
            color: #8B1538;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .form-links a:hover {
            text-decoration: underline;
        }

        .tutorials-section {
            border-top: 1px solid #e1e8ed;
            padding-top: 1.5rem;
        }

        .tutorials-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .tutorials-list {
            list-style: none;
        }

        .tutorials-list li {
            margin-bottom: 0.5rem;
        }

        .tutorials-list a {
            color: #34495e;
            text-decoration: none;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 5px 0;
            transition: color 0.3s ease;
        }

        .tutorials-list a:hover {
            color: #8B1538;
        }

        .tutorials-list i {
            font-size: 0.8rem;
            color: #8B1538;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
                padding: 1.5rem 1rem;
            }

            .nav-links {
                gap: 15px;
            }

            .login-container {
                margin: 1rem;
                padding: 2rem;
            }

            .welcome-title {
                font-size: 1.8rem;
            }

            .form-links {
                flex-direction: column;
                gap: 0.5rem;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="logo-section">
            <div class="logo">
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
            <div class="title">MARINA - Secretaría de Marina</div>
        </div>
        <nav class="nav-links">
            <a href="#tramites">Trámites</a>
            <a href="#gobierno">Gobierno</a>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="login-container">
            <div class="logo-branding">
                <div class="logo-container">
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
                <div class="branding-title">Certificación Médica Digital</div>
                <div class="branding-subtitle">Sistema Electrónico de Evaluación Médica</div>
            </div>
            
            <h1 class="welcome-title">¡Bienvenido!</h1>
            
            <form action="#" method="POST">
                <div class="form-group">
                    <div class="input-container">
                        <i class="fas fa-envelope"></i>
                        <input type="email" class="form-input" placeholder="Correo electrónico" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="input-container">
                        <i class="fas fa-lock"></i>
                        <input type="password" class="form-input" placeholder="Contraseña" required>
                    </div>
                </div>
                
                <button type="submit" class="login-btn">Ingresar</button>
                
                <div class="form-links">
                    <a href="#recovery">Recuperación de cuenta</a>
                    <a href="/registro">Registrarme</a>
                </div>
            </form>
            
            <div class="tutorials-section">
                <h3 class="tutorials-title">Tutoriales</h3>
                <ul class="tutorials-list">
                    <li><a href="#tutorial-recovery"><i class="fas fa-circle"></i> Recuperar Cuenta</a></li>
                    <li><a href="#tutorial-appointments"><i class="fas fa-circle"></i> Registro de Citas</a></li>
                    <li><a href="#tutorial-personal-data"><i class="fas fa-circle"></i> Registro de Datos Personales</a></li>
                    <li><a href="#tutorial-agenda"><i class="fas fa-circle"></i> Registro en la Agenda</a></li>
                </ul>
            </div>
        </div>
    </main>
</body>
</html>