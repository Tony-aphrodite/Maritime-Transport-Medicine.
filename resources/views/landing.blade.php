<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SEMAR - Servicio de Medicina del Transporte Marítimo</title>
    <link rel="stylesheet" href="{{ asset('assets/css/landing.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <header>
        <div class="container nav-container">
            <div class="logo">
                <img src="{{ asset('assets/img/SEMAR-1.png') }}" alt="Logo SEMAR">
            </div>
            <nav class="desktop-nav">
                <a href="#">Inicio</a>
                <a href="#quienes-somos">¿Quiénes somos?</a>
                <a href="#contacto">Contáctenos</a>
                <a href="#registro" class="btn-login" id="nav-login-btn">Ingresar</a>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="hero-slide-container">
            <div class="slide slide-1"></div>
            <div class="slide slide-2"></div>
            <div class="slide slide-3"></div>
        </div>

        <div class="container hero-content-overlay">
            <div class="hero-grid">
                <div class="hero-text">
                    <span class="badge">Disponible 24/7</span>
                    <h1>Servicio de Medicina del Transporte Marítimo</h1>
                    <p>Plataforma oficial para consultas y dictamen médico. Obtén tu certificado con validez SCT desde cualquier zona petrolera o de telecomunicaciones.</p>

                    <div class="hero-features">
                        <div class="f-item"><span>✓</span> Validez oficial SCT / STCW 1978</div>
                        <div class="f-item"><span>✓</span> Videollamada con médicos certificados</div>
                        <div class="f-item"><span>✓</span> Resultados y certificado digital</div>
                    </div>
                </div>

                <div class="auth-card" id="registro">
                    <!-- Login Form (Default - 2 fields) -->
                    <div id="login-form-container">
                        <h3>Iniciar Sesión</h3>
                        <p>Ingresa a tu cuenta para continuar</p>

                        <div id="login-alert" class="alert" style="display: none;"></div>

                        <form id="login-form">
                            <div class="input-group">
                                <label>Correo Electrónico</label>
                                <input type="email" name="email" id="login-email" placeholder="usuario@transporte.com" required>
                            </div>
                            <div class="input-group">
                                <label>Contraseña</label>
                                <input type="password" name="password" id="login-password" placeholder="Tu contraseña" required>
                            </div>
                            <button type="submit" class="btn-cta-main" id="login-btn">
                                <span class="btn-text">Ingresar</span>
                                <span class="btn-loader" style="display: none;"><i class="fas fa-spinner fa-spin"></i></span>
                            </button>
                        </form>
                        <p class="form-footer">¿No tienes cuenta? <a href="#" id="show-register">Regístrate aquí</a></p>
                    </div>

                    <!-- Register Form (3 fields) -->
                    <div id="register-form-container" style="display: none;">
                        <h3>Crear cuenta nueva</h3>
                        <p>Regístrate para agendar tu cita hoy mismo</p>

                        <div id="register-alert" class="alert" style="display: none;"></div>

                        <form id="register-form">
                            <div class="input-group">
                                <label>Correo Electrónico</label>
                                <input type="email" name="email" id="register-email" placeholder="usuario@transporte.com" required>
                            </div>
                            <div class="input-group">
                                <label>Contraseña</label>
                                <input type="password" name="password" id="register-password" placeholder="Crea una contraseña" required>
                            </div>
                            <div class="input-group">
                                <label>Confirmar Contraseña</label>
                                <input type="password" name="password_confirmation" id="register-password-confirm" placeholder="Confirma tu contraseña" required>
                            </div>
                            <button type="submit" class="btn-cta-main" id="register-btn">
                                <span class="btn-text">¡Registrarme Ahora!</span>
                                <span class="btn-loader" style="display: none;"><i class="fas fa-spinner fa-spin"></i></span>
                            </button>
                        </form>
                        <p class="form-footer">¿Ya tienes cuenta? <a href="#" id="show-login">Inicia sesión</a></p>
                    </div>

                    <!-- Email Verification Message -->
                    <div id="verification-message-container" style="display: none;">
                        <div class="verification-icon">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                        <h3>¡Verifica tu correo!</h3>
                        <p>Hemos enviado un enlace de verificación a:</p>
                        <p class="verification-email" id="sent-email"></p>
                        <p class="verification-instruction">Por favor revisa tu bandeja de entrada y haz clic en el enlace para activar tu cuenta.</p>
                        <button type="button" class="btn-cta-secondary" id="resend-verification">
                            <span class="btn-text">Reenviar correo de verificación</span>
                            <span class="btn-loader" style="display: none;"><i class="fas fa-spinner fa-spin"></i></span>
                        </button>
                        <p class="form-footer"><a href="#" id="back-to-login">Volver al inicio de sesión</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="split-content-section">
        <div class="container split-grid">

            <div class="split-left-col">
                <img src="{{ asset('assets/img/Plataforma-para-consultas-y-dictamen-medico-SEMAR.webp') }}" alt="Plataforma SEMAR" class="rounded-feature-img">

                <ul class="gold-check-list">
                    <li>Disponible 24/7 para agendamiento de citas.</li>
                    <li>Valoración medica.</li>
                    <li>Análisis de resultados de estudios médicos.</li>
                    <li>Dictamen de aptitud medica para el servicio en el transporte marítimo.</li>
                    <li>Perfil de Usuario.</li>
                    <li>Certificado médico digital.</li>
                </ul>
            </div>

            <div class="split-right-col">
                <h2 class="split-title">Realice su examen médico.</h2>

                <div class="split-body-text">
                    <p>Los exámenes médicos ahora están disponibles en línea. Agende una cita online: sus datos médicos se conservarán de forma segura sin necesidad de rellenar formularios en papel.</p>
                    <p>Será conectado en el momento de la cita en una videollamada con un médico para dictaminar su aptitud para labores en la Mar de acuerdo a la Regla I/9, del Convenio STCW 1978, en su forma enmendada.</p>
                    <p>Agende una cita para concertar una videollamada en una plataforma alternativa.</p>
                </div>

                <a href="#registro" class="btn-gold-cta">Iniciar ahora</a>
            </div>

        </div>
    </section>

    <section class="about-us"></section>
    <section id="quienes-somos" class="about-us">
        <div class="container">
            <div class="about-content">
                <h2 class="section-title-dark">¿Quiénes Somos?</h2>
                <p class="about-lead">Autoridad y Compromiso con la Seguridad Marítima Nacional</p>
                <div class="about-text">
                    <p>En <strong>Latitud Medica</strong>, operamos bajo los lineamientos de la Dirección General de Marina Mercante, regulando las políticas que promueven la seguridad del transporte marítimo y fomentan la competitividad del comercio exterior en México.</p>
                    <p>Nuestra plataforma digital es la respuesta moderna a las necesidades del sector, garantizando que cada dictamen de aptitud médica cumpla estrictamente con el marco regulatorio nacional y el <strong>Convenio STCW 1978</strong>.</p>
                </div>
                <div class="about-badges">
                    <span><i class="fas fa-shield-alt"></i> Validez Oficial</span>
                    <span><i class="fas fa-anchor"></i> Sector Marítimo</span>
                    <span><i class="fas fa-user-md"></i> Médicos Certificados</span>
                </div>
            </div>
        </div>
    </section>

    <section class="how-it-works full-screen-section">
        <div class="wide-wrapper">
            <h2 class="section-title-large">¿Cómo funciona nuestra plataforma?</h2>

            <div class="steps-three-columns">

                <div class="steps-left">
                    <div class="step-item">
                        <div class="step-num">1</div>
                        <div class="step-content">
                            <h4>Agende su cita en el sitio web creando su cuenta.</h4>
                            <p>¡Regístrese rápidamente en la plataforma y agende una cita para consulta, valoración y dictamen médico!</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-num">2</div>
                        <div class="step-content">
                            <h4>Capture la información y archivos requeridos.</h4>
                            <p>Complete el cuestionario y adjunte los archivos solicitados y resultados de laboratorio.<br>Una vez que cuente con todos los requisitos completos, agende una cita para una su valoración mediante videollamada.</p>
                        </div>
                    </div>
                </div>

                <div class="steps-center-image">
                    <div class="video-overlay-wrapper">
                        <img src="{{ asset('assets/img/4_Home_Doctors_Online_Consultation-Phone_Video.gif') }}" alt="Videollamada con doctora" class="video-gif">

                        <img src="{{ asset('assets/img/4_Home_Doctors_Online_Consultation-Testimonials_01.jpg') }}" alt="Mi vista de paciente" class="patient-pip">

                        <div class="video-controls">
                            <button class="control-btn"><i class="fas fa-microphone-slash"></i></button>
                            <button class="control-btn end-call-btn"><i class="fas fa-phone-slash"></i></button>
                            <button class="control-btn"><i class="fas fa-video"></i></button>
                        </div>
                    </div>
                </div>

                <div class="steps-right">
                    <div class="step-item">
                        <div class="step-num">3</div>
                        <div class="step-content">
                            <h4>Realice el pago administrativo de su cita.</h4>
                            <p>Para confirmar su cita es necesario realizar el pago de la misma. Pagar su cita a traves del sitio es sumamente sencillo, se proporciona una amplia variedad de medios de pago: tarjetas de crédito, débito, transferencias bancarias y billeteras digitales. ¡Rápido y seguro para todos los usuarios!</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-num">4</div>
                        <div class="step-content">
                            <h4>Reciba la información de su cita.</h4>
                            <p>Después de realizar el pago de su cita, usted recibirá toda la información de su agendamiento en su correo electrónico. Además, le enviaremos las credenciales de videollamada para conectarse fácilmente con el profesional de la salud. ¡Su valoración medica para realizar labores en la Mar es mas fácil ahora!</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="contacto" class="contact-modern">
        <div class="container">
            <div class="contact-wrapper">
                <div class="contact-info-card">
                    <h3>¿Necesitas asesoría inmediata?</h3>
                    <p>Nuestros especialistas están listos para guiarte en tu proceso de certificación médica.</p>

                    <div class="contact-methods">
                        <div class="method-item">
                            <i class="fas fa-envelope"></i>
                            <span>soporte@medicinasemar.com</span>
                        </div>
                        <div class="method-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Ciudad de México / Puertos Principales</span>
                        </div>
                    </div>

                </div>

                <div class="contact-action">
                    <div class="whatsapp-bubble">
                        <div class="pulse-ring"></div>
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <h4>Contacto Directo</h4>
                    <p>Chatea con un asesor en tiempo real</p>
                    <a href="https://wa.me/5218991136753?text=Hola,%20necesito%20información%20sobre%20el%20dictamen%20médico%20marítimo."
                        class="btn-whatsapp"
                        target="_blank">
                        Iniciar Chat ahora
                    </a>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container footer-grid">
            <div class="footer-info">
                <img src="{{ asset('assets/img/SEMAR-1.png') }}" alt="Logo SEMAR" class="footer-logo">
                <p>Dirección General de Marina Mercante. Regulando la seguridad del transporte marítimo nacional.</p>
            </div>
            <div class="footer-links">
                <h4>Enlaces</h4>
                <a href="#">Política de Privacidad</a>
                <a href="#">Términos y Condiciones</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© Secretaría de Marina - Todos los derechos reservados</p>
        </div>
    </footer>

    <script>
    // =============================================
    // Image Animation Observer
    // =============================================
    const observerOptions = {
        threshold: 0.2
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('appear');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    const featureImg = document.querySelector('.rounded-feature-img');
    if (featureImg) {
        observer.observe(featureImg);
    }

    // =============================================
    // Auth Form Toggle & AJAX Handlers
    // =============================================
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Elements
        const loginFormContainer = document.getElementById('login-form-container');
        const registerFormContainer = document.getElementById('register-form-container');
        const verificationContainer = document.getElementById('verification-message-container');

        const showRegisterLink = document.getElementById('show-register');
        const showLoginLink = document.getElementById('show-login');
        const backToLoginLink = document.getElementById('back-to-login');
        const navLoginBtn = document.getElementById('nav-login-btn');

        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        const resendVerificationBtn = document.getElementById('resend-verification');

        const loginAlert = document.getElementById('login-alert');
        const registerAlert = document.getElementById('register-alert');
        const sentEmailDisplay = document.getElementById('sent-email');

        let registeredEmail = '';

        // =============================================
        // Form Toggle Functions
        // =============================================
        function showLogin() {
            loginFormContainer.style.display = 'block';
            registerFormContainer.style.display = 'none';
            verificationContainer.style.display = 'none';
            hideAlert(loginAlert);
            hideAlert(registerAlert);
        }

        function showRegister() {
            loginFormContainer.style.display = 'none';
            registerFormContainer.style.display = 'block';
            verificationContainer.style.display = 'none';
            hideAlert(loginAlert);
            hideAlert(registerAlert);
        }

        function showVerificationMessage(email) {
            loginFormContainer.style.display = 'none';
            registerFormContainer.style.display = 'none';
            verificationContainer.style.display = 'block';
            sentEmailDisplay.textContent = email;
            registeredEmail = email;
        }

        // Event Listeners for Toggle
        showRegisterLink.addEventListener('click', function(e) {
            e.preventDefault();
            showRegister();
        });

        showLoginLink.addEventListener('click', function(e) {
            e.preventDefault();
            showLogin();
        });

        backToLoginLink.addEventListener('click', function(e) {
            e.preventDefault();
            showLogin();
        });

        navLoginBtn.addEventListener('click', function(e) {
            e.preventDefault();
            showLogin();
            document.getElementById('registro').scrollIntoView({ behavior: 'smooth' });
        });

        // =============================================
        // Alert Functions
        // =============================================
        function showAlert(element, message, type) {
            element.className = 'alert alert-' + type;
            element.innerHTML = message;
            element.style.display = 'block';
        }

        function hideAlert(element) {
            element.style.display = 'none';
        }

        function setButtonLoading(button, loading) {
            const btnText = button.querySelector('.btn-text');
            const btnLoader = button.querySelector('.btn-loader');
            if (loading) {
                btnText.style.display = 'none';
                btnLoader.style.display = 'inline-block';
                button.disabled = true;
            } else {
                btnText.style.display = 'inline';
                btnLoader.style.display = 'none';
                button.disabled = false;
            }
        }

        // =============================================
        // Login Form Handler
        // =============================================
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const loginBtn = document.getElementById('login-btn');
            const email = document.getElementById('login-email').value;
            const password = document.getElementById('login-password').value;

            setButtonLoading(loginBtn, true);
            hideAlert(loginAlert);

            try {
                const response = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showAlert(loginAlert, '¡Inicio de sesion exitoso! Redirigiendo...', 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect || '/dashboard';
                    }, 1000);
                } else {
                    if (data.needs_verification) {
                        showVerificationMessage(data.email || email);
                    } else {
                        showAlert(loginAlert, data.message || 'Credenciales incorrectas', 'error');
                    }
                }
            } catch (error) {
                showAlert(loginAlert, 'Error de conexión. Por favor intenta de nuevo.', 'error');
            } finally {
                setButtonLoading(loginBtn, false);
            }
        });

        // =============================================
        // Register Form Handler
        // =============================================
        registerForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const registerBtn = document.getElementById('register-btn');
            const email = document.getElementById('register-email').value;
            const password = document.getElementById('register-password').value;
            const passwordConfirm = document.getElementById('register-password-confirm').value;

            setButtonLoading(registerBtn, true);
            hideAlert(registerAlert);

            // Client-side validation
            if (password !== passwordConfirm) {
                showAlert(registerAlert, 'Las contraseñas no coinciden', 'error');
                setButtonLoading(registerBtn, false);
                return;
            }

            if (password.length < 8) {
                showAlert(registerAlert, 'La contraseña debe tener al menos 8 caracteres', 'error');
                setButtonLoading(registerBtn, false);
                return;
            }

            try {
                const response = await fetch('/api/auth/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email,
                        password,
                        password_confirmation: passwordConfirm
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showVerificationMessage(email);
                } else {
                    let errorMessage = data.message || 'Error al registrar';
                    if (data.errors) {
                        errorMessage = Object.values(data.errors).flat().join('<br>');
                    }
                    showAlert(registerAlert, errorMessage, 'error');
                }
            } catch (error) {
                showAlert(registerAlert, 'Error de conexión. Por favor intenta de nuevo.', 'error');
            } finally {
                setButtonLoading(registerBtn, false);
            }
        });

        // =============================================
        // Resend Verification Email Handler
        // =============================================
        resendVerificationBtn.addEventListener('click', async function() {
            setButtonLoading(resendVerificationBtn, true);

            try {
                const response = await fetch('/api/auth/resend-verification', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email: registeredEmail })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    alert('¡Correo de verificación reenviado! Revisa tu bandeja de entrada.');
                } else {
                    alert(data.message || 'No se pudo reenviar el correo');
                }
            } catch (error) {
                alert('Error de conexión. Por favor intenta de nuevo.');
            } finally {
                setButtonLoading(resendVerificationBtn, false);
            }
        });

        // =============================================
        // Check for URL parameters (after email verification)
        // =============================================
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('verified') === 'true') {
            showLogin();
            showAlert(loginAlert, '¡Tu correo ha sido verificado! Ya puedes iniciar sesión.', 'success');
        }
        if (urlParams.get('mode') === 'register') {
            showRegister();
        }
    });
    </script>

</body>
</html>
