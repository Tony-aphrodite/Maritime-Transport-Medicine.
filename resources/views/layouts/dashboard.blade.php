<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel de Usuario') - Medicina SEMAR</title>
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
</head>
<body>

    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-logo">
                <img src="{{ asset('assets/img/SEMAR-1.png') }}" alt="SEMAR">
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="fas fa-calendar-alt"></i> Citas</a>
                <a href="#"><i class="fas fa-history"></i> Historial de Citas</a>
                <a href="{{ route('profile.show') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}"><i class="fas fa-user-circle"></i> Mi Perfil</a>
                <a href="#"><i class="fas fa-file-medical"></i> Declaracion Medica</a>
                <a href="#"><i class="fas fa-folder-open"></i> Mis Archivos</a>
                <a href="#"><i class="fas fa-certificate"></i> Mis Certificados</a>
                <a href="#"><i class="fas fa-receipt"></i> Recibos</a>
                <div class="nav-divider"></div>
                <a href="#" onclick="event.preventDefault(); openPasswordModal();"><i class="fas fa-key"></i> Cambio de Contrasena</a>
                <a href="#" class="logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Cerrar Sesion</a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="dash-header">
                <div class="user-welcome">
                    @php
                        $userAvatarUrl = auth()->user()->profile_photo
                            ? asset('storage/' . auth()->user()->profile_photo)
                            : asset('assets/img/user-avatar.jpg');
                    @endphp
                    <img src="{{ $userAvatarUrl }}" alt="Usuario" class="user-avatar" id="dashboardAvatar">
                    <div class="welcome-text">
                        <span>Bienvenido de nuevo,</span>
                        <h2>{{ auth()->user()->full_name ?? auth()->user()->name ?? 'Usuario' }}</h2>
                    </div>
                </div>
                <div class="timezone-selector">
                    <i class="fas fa-globe"></i>
                    <select id="timezone">
                        <option>Zona Central / Ciudad de Mexico (GMT-6)</option>
                        <option>Tiempo Universal Coordinado (UTC)</option>
                    </select>
                </div>
            </header>

            @yield('content')
        </main>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('api.auth.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Password Change Modal -->
    <div id="passwordModal" class="modal-overlay" style="display: none;">
        <div class="modal-container">
            <div class="modal-header">
                <h3><i class="fas fa-key"></i> Cambio de Contrasena</h3>
                <button type="button" class="modal-close" onclick="closePasswordModal()">&times;</button>
            </div>
            <form id="passwordForm" onsubmit="handlePasswordChange(event)">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="currentPassword"><i class="fas fa-lock"></i> Contrasena Actual</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="currentPassword" name="current_password" required placeholder="Ingrese su contrasena actual">
                            <button type="button" class="toggle-password" onclick="togglePassword('currentPassword')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="newPassword"><i class="fas fa-key"></i> Nueva Contrasena</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="newPassword" name="new_password" required placeholder="Minimo 8 caracteres" minlength="8">
                            <button type="button" class="toggle-password" onclick="togglePassword('newPassword')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength" id="passwordStrength"></div>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword"><i class="fas fa-check-circle"></i> Confirmar Nueva Contrasena</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="confirmPassword" name="new_password_confirmation" required placeholder="Repita la nueva contrasena">
                            <button type="button" class="toggle-password" onclick="togglePassword('confirmPassword')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div id="passwordMatch" class="password-match"></div>
                    </div>
                    <div id="passwordError" class="error-message" style="display: none;"></div>
                    <div id="passwordSuccess" class="success-message" style="display: none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closePasswordModal()">Cancelar</button>
                    <button type="submit" class="btn-primary" id="submitPasswordBtn">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            animation: fadeIn 0.2s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-container {
            background: white;
            border-radius: 16px;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-header {
            background: linear-gradient(135deg, #1A5A8A 0%, #2F7DB2 100%);
            color: white;
            padding: 1.25rem 1.5rem;
            border-radius: 16px 16px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-close {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 1.5rem;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-body .form-group {
            margin-bottom: 1.25rem;
        }

        .modal-body .form-group:last-of-type {
            margin-bottom: 0.5rem;
        }

        .modal-body label {
            display: block;
            font-weight: 600;
            color: #1A5A8A;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .modal-body label i {
            margin-right: 0.25rem;
        }

        .password-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .modal-body input {
            width: 100%;
            padding: 0.75rem 2.5rem 0.75rem 1rem;
            border: 2px solid #E5E7EB;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .modal-body input:focus {
            outline: none;
            border-color: #2F7DB2;
            box-shadow: 0 0 0 3px rgba(47, 125, 178, 0.15);
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            background: none;
            border: none;
            color: #9CA3AF;
            cursor: pointer;
            padding: 5px;
        }

        .toggle-password:hover {
            color: #2F7DB2;
        }

        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.8rem;
            min-height: 1.2rem;
        }

        .password-match {
            margin-top: 0.5rem;
            font-size: 0.8rem;
            min-height: 1.2rem;
        }

        .strength-weak { color: #EF4444; }
        .strength-medium { color: #F59E0B; }
        .strength-strong { color: #10B981; }

        .match-error { color: #EF4444; }
        .match-success { color: #10B981; }

        .error-message {
            background: #FEF2F2;
            color: #991B1B;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            margin-top: 1rem;
            border: 1px solid #FECACA;
        }

        .success-message {
            background: #F0FDF4;
            color: #166534;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            margin-top: 1rem;
            border: 1px solid #BBF7D0;
        }

        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #E5E7EB;
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        .modal-footer .btn-secondary {
            padding: 0.6rem 1.25rem;
            background: #F3F4F6;
            color: #374151;
            border: 2px solid #E5E7EB;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .modal-footer .btn-secondary:hover {
            background: #E5E7EB;
        }

        .modal-footer .btn-primary {
            padding: 0.6rem 1.25rem;
            background: linear-gradient(135deg, #2F7DB2 0%, #5BA4D9 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-footer .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(47, 125, 178, 0.4);
        }

        .modal-footer .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
    </style>

    <script>
        function openPasswordModal() {
            document.getElementById('passwordModal').style.display = 'flex';
            document.getElementById('passwordForm').reset();
            document.getElementById('passwordError').style.display = 'none';
            document.getElementById('passwordSuccess').style.display = 'none';
            document.getElementById('passwordStrength').innerHTML = '';
            document.getElementById('passwordMatch').innerHTML = '';
        }

        function closePasswordModal() {
            document.getElementById('passwordModal').style.display = 'none';
        }

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        // Password strength checker
        document.getElementById('newPassword').addEventListener('input', function() {
            const password = this.value;
            const strengthDiv = document.getElementById('passwordStrength');

            if (password.length === 0) {
                strengthDiv.innerHTML = '';
                return;
            }

            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;

            if (strength <= 2) {
                strengthDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Contrasena debil';
                strengthDiv.className = 'password-strength strength-weak';
            } else if (strength <= 3) {
                strengthDiv.innerHTML = '<i class="fas fa-check"></i> Contrasena media';
                strengthDiv.className = 'password-strength strength-medium';
            } else {
                strengthDiv.innerHTML = '<i class="fas fa-shield-alt"></i> Contrasena fuerte';
                strengthDiv.className = 'password-strength strength-strong';
            }

            // Check match if confirm field has value
            checkPasswordMatch();
        });

        // Password match checker
        document.getElementById('confirmPassword').addEventListener('input', checkPasswordMatch);

        function checkPasswordMatch() {
            const newPass = document.getElementById('newPassword').value;
            const confirmPass = document.getElementById('confirmPassword').value;
            const matchDiv = document.getElementById('passwordMatch');

            if (confirmPass.length === 0) {
                matchDiv.innerHTML = '';
                return;
            }

            if (newPass === confirmPass) {
                matchDiv.innerHTML = '<i class="fas fa-check-circle"></i> Las contrasenas coinciden';
                matchDiv.className = 'password-match match-success';
            } else {
                matchDiv.innerHTML = '<i class="fas fa-times-circle"></i> Las contrasenas no coinciden';
                matchDiv.className = 'password-match match-error';
            }
        }

        function handlePasswordChange(event) {
            event.preventDefault();

            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const errorDiv = document.getElementById('passwordError');
            const successDiv = document.getElementById('passwordSuccess');
            const submitBtn = document.getElementById('submitPasswordBtn');

            // Validate passwords match
            if (newPassword !== confirmPassword) {
                errorDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Las contrasenas no coinciden.';
                errorDiv.style.display = 'block';
                successDiv.style.display = 'none';
                return;
            }

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
            errorDiv.style.display = 'none';
            successDiv.style.display = 'none';

            // Send request
            fetch('{{ route("profile.password.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    current_password: currentPassword,
                    new_password: newPassword,
                    new_password_confirmation: confirmPassword
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    successDiv.innerHTML = '<i class="fas fa-check-circle"></i> ' + data.message;
                    successDiv.style.display = 'block';
                    errorDiv.style.display = 'none';

                    // Close modal after 2 seconds
                    setTimeout(() => {
                        closePasswordModal();
                    }, 2000);
                } else {
                    errorDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + data.message;
                    errorDiv.style.display = 'block';
                    successDiv.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Error al actualizar la contrasena. Por favor intente de nuevo.';
                errorDiv.style.display = 'block';
                successDiv.style.display = 'none';
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save"></i> Guardar Cambios';
            });
        }

        // Close modal when clicking outside
        document.getElementById('passwordModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePasswordModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePasswordModal();
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
