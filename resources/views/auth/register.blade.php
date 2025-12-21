<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Crear Cuenta - Maritime Transport Medicine</title>
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

        .register-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            width: 100%;
            max-width: 450px;
            overflow: hidden;
        }

        .register-header {
            background: linear-gradient(135deg, #0F4C75 0%, #3282B8 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .register-header .logo {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .register-header .logo i {
            font-size: 40px;
            color: #0F4C75;
        }

        .register-header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .register-header p {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .register-form {
            padding: 40px 30px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #1B262C;
            font-size: 0.9rem;
        }

        .form-group label i {
            margin-right: 8px;
            color: #3282B8;
        }

        .input-wrapper {
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            padding-right: 45px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .form-control:focus {
            outline: none;
            border-color: #3282B8;
            background: white;
            box-shadow: 0 0 0 4px rgba(50, 130, 184, 0.1);
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #64748b;
            font-size: 1rem;
            padding: 5px;
        }

        .toggle-password:hover {
            color: #3282B8;
        }

        .password-strength {
            margin-top: 8px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .strength-bar {
            flex: 1;
            height: 4px;
            background: #e2e8f0;
            border-radius: 2px;
            overflow: hidden;
        }

        .strength-bar-fill {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
        }

        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #0F4C75 0%, #3282B8 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(15, 76, 117, 0.3);
        }

        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .error-message {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .login-link {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #e2e8f0;
            font-size: 0.9rem;
            color: #64748b;
        }

        .login-link a {
            color: #3282B8;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .terms-text {
            font-size: 0.8rem;
            color: #64748b;
            text-align: center;
            margin-top: 16px;
            line-height: 1.5;
        }

        .terms-text a {
            color: #3282B8;
            text-decoration: none;
        }

        .password-match {
            margin-top: 8px;
            font-size: 0.85rem;
            display: none;
        }

        .password-match.show {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .password-match.success {
            color: #10b981;
        }

        .password-match.error {
            color: #ef4444;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <div class="logo">
                <i class="fas fa-ship"></i>
            </div>
            <h1>Crear Nueva Cuenta</h1>
            <p>Maritime Transport Medicine</p>
        </div>

        <form class="register-form" method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf

            @if ($errors->any())
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <div class="form-group">
                <label for="email">
                    <i class="fas fa-envelope"></i>
                    Correo Electrónico
                </label>
                <input type="email"
                       class="form-control"
                       id="email"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="correo@ejemplo.com"
                       required
                       autofocus>
            </div>

            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i>
                    Contraseña
                </label>
                <div class="input-wrapper">
                    <input type="password"
                           class="form-control"
                           id="password"
                           name="password"
                           placeholder="Mínimo 8 caracteres"
                           minlength="8"
                           required>
                    <button type="button" class="toggle-password" onclick="togglePassword('password', this)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="password-strength">
                    <div class="strength-bar">
                        <div class="strength-bar-fill" id="strengthBar"></div>
                    </div>
                    <span id="strengthText">Ingrese contraseña</span>
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirmation">
                    <i class="fas fa-lock"></i>
                    Confirmar Contraseña
                </label>
                <div class="input-wrapper">
                    <input type="password"
                           class="form-control"
                           id="password_confirmation"
                           name="password_confirmation"
                           placeholder="Confirme su contraseña"
                           minlength="8"
                           required>
                    <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation', this)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="password-match" id="passwordMatch">
                    <i class="fas fa-check-circle"></i>
                    <span>Las contraseñas coinciden</span>
                </div>
            </div>

            <button type="submit" class="submit-btn" id="submitBtn">
                <i class="fas fa-user-plus"></i>
                Registrarse
            </button>

            <p class="terms-text">
                Al registrarse, acepta nuestros
                <a href="#">Términos de Servicio</a> y
                <a href="#">Política de Privacidad</a>
            </p>

            <div class="login-link">
                ¿Ya tiene una cuenta? <a href="{{ route('login') }}">Iniciar Sesión</a>
            </div>
        </form>
    </div>

    <script>
        function togglePassword(fieldId, button) {
            const field = document.getElementById(fieldId);
            const icon = button.querySelector('i');

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Password strength checker
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');
        const passwordMatch = document.getElementById('passwordMatch');

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let text = '';
            let color = '';

            if (password.length === 0) {
                text = 'Ingrese contraseña';
                color = '#e2e8f0';
                strength = 0;
            } else if (password.length < 8) {
                text = 'Muy corta';
                color = '#ef4444';
                strength = 25;
            } else {
                // Check for different character types
                if (/[a-z]/.test(password)) strength += 25;
                if (/[A-Z]/.test(password)) strength += 25;
                if (/[0-9]/.test(password)) strength += 25;
                if (/[^a-zA-Z0-9]/.test(password)) strength += 25;

                if (strength <= 25) {
                    text = 'Débil';
                    color = '#ef4444';
                } else if (strength <= 50) {
                    text = 'Regular';
                    color = '#f59e0b';
                } else if (strength <= 75) {
                    text = 'Buena';
                    color = '#10b981';
                } else {
                    text = 'Excelente';
                    color = '#059669';
                }
            }

            strengthBar.style.width = strength + '%';
            strengthBar.style.backgroundColor = color;
            strengthText.textContent = text;
            strengthText.style.color = color;

            // Check password match
            checkPasswordMatch();
        });

        confirmInput.addEventListener('input', checkPasswordMatch);

        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirm = confirmInput.value;

            if (confirm.length === 0) {
                passwordMatch.classList.remove('show', 'success', 'error');
                return;
            }

            passwordMatch.classList.add('show');

            if (password === confirm) {
                passwordMatch.classList.remove('error');
                passwordMatch.classList.add('success');
                passwordMatch.innerHTML = '<i class="fas fa-check-circle"></i><span>Las contraseñas coinciden</span>';
            } else {
                passwordMatch.classList.remove('success');
                passwordMatch.classList.add('error');
                passwordMatch.innerHTML = '<i class="fas fa-times-circle"></i><span>Las contraseñas no coinciden</span>';
            }
        }

        // Form submission
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = passwordInput.value;
            const confirm = confirmInput.value;

            if (password !== confirm) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
                return false;
            }

            if (password.length < 8) {
                e.preventDefault();
                alert('La contraseña debe tener al menos 8 caracteres');
                return false;
            }

            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Registrando...';
        });
    </script>
</body>
</html>
