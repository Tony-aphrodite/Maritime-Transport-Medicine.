<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación Facial - MARINA</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            background: linear-gradient(135deg, #0F4C75 0%, #3282B8 100%);
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 8px 32px rgba(15, 76, 117, 0.3);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 24px;
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

        .header-title {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
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

        /* Main Content */
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            text-align: center;
            margin-bottom: 2rem;
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
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
        }

        /* Verification Steps */
        .verification-steps {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .step-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.3);
            transition: all 0.3s ease;
        }

        .step-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
        }

        .step-header {
            background: linear-gradient(135deg, #0F4C75, #3282B8);
            color: white;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .step-number {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .step-title {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .step-content {
            padding: 2rem;
        }

        /* Camera/Upload Interface */
        .capture-interface {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .camera-container {
            position: relative;
            background: #f8f9fa;
            border: 3px dashed #dee2e6;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            min-height: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .camera-container.active {
            border-color: #0F4C75;
            background: #f0f8ff;
        }

        .camera-container.dragover {
            border-color: #3282B8;
            background: #e6f3ff;
            transform: scale(1.02);
        }

        .video-element, .preview-image {
            max-width: 100%;
            max-height: 250px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .camera-placeholder {
            color: #6c757d;
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .camera-instructions {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        /* Action Buttons */
        .camera-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
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
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(15, 76, 117, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #64748b, #475569);
            color: white;
            box-shadow: 0 8px 25px rgba(100, 116, 139, 0.3);
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #475569, #334155);
            transform: translateY(-2px);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        /* File Input Styling */
        .file-input-container {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }

        .file-input-container input[type=file] {
            position: absolute;
            left: -9999px;
        }

        .file-input-label {
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: linear-gradient(135deg, #64748b, #475569);
            color: white;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .file-input-label:hover {
            background: linear-gradient(135deg, #475569, #334155);
            transform: translateY(-2px);
        }

        /* Verification Results */
        .verification-section {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            padding: 2rem;
            margin-top: 2rem;
            text-align: center;
        }

        .verification-section.hidden {
            display: none;
        }

        .result-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .result-icon.success {
            color: #10b981;
        }

        .result-icon.failure {
            color: #ef4444;
        }

        .result-message {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .confidence-score {
            font-size: 1.1rem;
            color: #64748b;
            margin-bottom: 1.5rem;
        }

        .confidence-bar {
            width: 100%;
            height: 8px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
            margin: 0.5rem 0;
        }

        .confidence-fill {
            height: 100%;
            background: linear-gradient(90deg, #ef4444, #f59e0b, #10b981);
            border-radius: 4px;
            transition: width 1s ease;
        }

        /* Loading States */
        .loading-spinner {
            display: inline-block;
            width: 24px;
            height: 24px;
            border: 3px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top: 3px solid #ffffff;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .processing-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .processing-overlay.hidden {
            display: none;
        }

        .processing-content {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            text-align: center;
            max-width: 400px;
        }

        .processing-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid #e2e8f0;
            border-top: 4px solid #0F4C75;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        /* Error/Success Messages */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin: 1rem 0;
            font-weight: 500;
        }

        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-warning {
            background: #fffbeb;
            color: #92400e;
            border: 1px solid #fed7aa;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .verification-steps {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .container {
                padding: 1rem;
            }

            .main-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .camera-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .btn {
                width: 100%;
                justify-content: center;
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
                    <path d="M60 10 L85 20 L85 50 Q85 75 60 100 Q35 75 35 50 L35 20 Z" 
                          fill="#0F4C75" stroke="#3282B8" stroke-width="2"/>
                    <path d="M60 15 L80 23 L80 48 Q80 70 60 90 Q40 70 40 48 L40 23 Z" 
                          fill="white" opacity="0.95"/>
                    <rect x="57" y="35" width="6" height="20" fill="#0F4C75"/>
                    <rect x="50" y="42" width="20" height="6" fill="#0F4C75"/>
                    <g stroke="#3282B8" stroke-width="1.5" fill="none" opacity="0.8">
                        <path d="M45 30 L50 30 L52 32 L55 32"/>
                        <path d="M65 32 L68 32 L70 30 L75 30"/>
                        <circle cx="47" cy="30" r="1.5" fill="#3282B8"/>
                        <circle cx="73" cy="30" r="1.5" fill="#3282B8"/>
                    </g>
                    <path d="M40 70 Q50 65 60 70 T80 70" 
                          stroke="#BBE1FA" stroke-width="2" fill="none"/>
                    <circle cx="60" cy="25" r="2" fill="#3282B8"/>
                </svg>
            </div>
            <div class="header-title">MARINA - Verificación Facial</div>
        </div>
        <nav class="header-nav">
            <a href="/registro"><i class="fas fa-arrow-left"></i> Volver al Registro</a>
        </nav>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Verificación de Identidad Facial</h1>
            <p class="page-subtitle">Compare su rostro con la fotografía de su INE para verificar su identidad</p>
        </div>

        <!-- Verification Steps -->
        <div class="verification-steps">
            <!-- Step 1: Selfie Capture -->
            <div class="step-card">
                <div class="step-header">
                    <div class="step-number">1</div>
                    <div class="step-title">Capture una Selfie</div>
                </div>
                <div class="step-content">
                    <div class="capture-interface">
                        <div class="camera-container" id="selfieContainer">
                            <div class="camera-placeholder">
                                <i class="fas fa-camera"></i>
                            </div>
                            <p class="camera-instructions">
                                Tome una selfie o seleccione una foto desde su dispositivo
                            </p>
                            <video id="selfieVideo" class="video-element" autoplay muted style="display: none;"></video>
                            <img id="selfiePreview" class="preview-image" style="display: none;" alt="Selfie Preview">
                            <canvas id="selfieCanvas" style="display: none;"></canvas>
                        </div>
                        
                        <div class="camera-actions">
                            <button id="startSelfieCamera" class="btn btn-primary">
                                <i class="fas fa-camera"></i>
                                Activar Cámara
                            </button>
                            <button id="captureSelfie" class="btn btn-success" style="display: none;">
                                <i class="fas fa-camera-retro"></i>
                                Tomar Foto
                            </button>
                            <button id="retakeSelfie" class="btn btn-secondary" style="display: none;">
                                <i class="fas fa-redo"></i>
                                Repetir
                            </button>
                            
                            <div class="file-input-container">
                                <input type="file" id="selfieUpload" accept="image/*" capture="user">
                                <label for="selfieUpload" class="file-input-label">
                                    <i class="fas fa-upload"></i>
                                    Subir Archivo
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: INE Photo Upload -->
            <div class="step-card">
                <div class="step-header">
                    <div class="step-number">2</div>
                    <div class="step-title">Subir Foto del INE</div>
                </div>
                <div class="step-content">
                    <div class="capture-interface">
                        <div class="camera-container" id="ineContainer" 
                             ondrop="handleDrop(event, 'ine')" 
                             ondragover="handleDragOver(event)" 
                             ondragenter="handleDragEnter(event)" 
                             ondragleave="handleDragLeave(event)">
                            <div class="camera-placeholder">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <p class="camera-instructions">
                                Suba una foto clara de la parte frontal de su INE/IFE<br>
                                Arrastre y suelte o haga clic para seleccionar
                            </p>
                            <img id="inePreview" class="preview-image" style="display: none;" alt="INE Preview">
                        </div>
                        
                        <div class="camera-actions">
                            <div class="file-input-container">
                                <input type="file" id="ineUpload" accept="image/*">
                                <label for="ineUpload" class="file-input-label">
                                    <i class="fas fa-upload"></i>
                                    Seleccionar INE
                                </label>
                            </div>
                            <button id="removeIne" class="btn btn-danger" style="display: none;">
                                <i class="fas fa-trash"></i>
                                Remover
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verification Button -->
        <div class="verification-section">
            <button id="startVerification" class="btn btn-primary" disabled style="font-size: 1.1rem; padding: 1rem 2rem;">
                <i class="fas fa-shield-check"></i>
                Iniciar Verificación Facial
            </button>
            
            <div id="verificationResults" class="hidden" style="margin-top: 2rem;">
                <div class="result-icon" id="resultIcon">
                    <i class="fas fa-question"></i>
                </div>
                <div class="result-message" id="resultMessage">Procesando...</div>
                <div class="confidence-score" id="confidenceScore">
                    Confianza: <span id="confidenceValue">0</span>%
                    <div class="confidence-bar">
                        <div class="confidence-fill" id="confidenceFill" style="width: 0%"></div>
                    </div>
                </div>
                <div class="camera-actions">
                    <button id="retryVerification" class="btn btn-secondary" style="display: none;">
                        <i class="fas fa-redo"></i>
                        Intentar de Nuevo
                    </button>
                    <button id="continueRegistration" class="btn btn-success" style="display: none;">
                        <i class="fas fa-arrow-right"></i>
                        Continuar Registro
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Processing Overlay -->
    <div class="processing-overlay hidden" id="processingOverlay">
        <div class="processing-content">
            <div class="processing-spinner"></div>
            <h3>Verificando Identidad</h3>
            <p>Comparando su rostro con la foto del INE...</p>
            <p style="font-size: 0.9rem; color: #64748b; margin-top: 1rem;">
                Este proceso puede tomar unos segundos
            </p>
        </div>
    </div>

    <script>
        // Global variables
        let selfieFile = null;
        let ineFile = null;
        let selfieStream = null;
        
        // DOM elements
        const selfieVideo = document.getElementById('selfieVideo');
        const selfieCanvas = document.getElementById('selfieCanvas');
        const selfiePreview = document.getElementById('selfiePreview');
        const inePreview = document.getElementById('inePreview');
        const startVerificationBtn = document.getElementById('startVerification');
        const processingOverlay = document.getElementById('processingOverlay');
        const verificationResults = document.getElementById('verificationResults');

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeEventListeners();
            checkVerificationReadiness();
        });

        function initializeEventListeners() {
            // Selfie camera controls
            document.getElementById('startSelfieCamera').addEventListener('click', startSelfieCamera);
            document.getElementById('captureSelfie').addEventListener('click', captureSelfie);
            document.getElementById('retakeSelfie').addEventListener('click', retakeSelfie);
            
            // File uploads
            document.getElementById('selfieUpload').addEventListener('change', handleSelfieUpload);
            document.getElementById('ineUpload').addEventListener('change', handleIneUpload);
            
            // INE controls
            document.getElementById('removeIne').addEventListener('click', removeIne);
            
            // Verification
            document.getElementById('startVerification').addEventListener('click', startFaceVerification);
            document.getElementById('retryVerification').addEventListener('click', retryVerification);
            document.getElementById('continueRegistration').addEventListener('click', continueRegistration);
        }

        async function startSelfieCamera() {
            try {
                selfieStream = await navigator.mediaDevices.getUserMedia({ 
                    video: { 
                        width: { ideal: 640 }, 
                        height: { ideal: 480 },
                        facingMode: 'user'
                    } 
                });
                
                selfieVideo.srcObject = selfieStream;
                selfieVideo.style.display = 'block';
                
                document.getElementById('startSelfieCamera').style.display = 'none';
                document.getElementById('captureSelfie').style.display = 'inline-flex';
                document.querySelector('#selfieContainer .camera-placeholder').style.display = 'none';
                document.querySelector('#selfieContainer .camera-instructions').style.display = 'none';
                
            } catch (err) {
                console.error('Error accessing camera:', err);
                showAlert('Error al acceder a la cámara: ' + err.message, 'error');
            }
        }

        function captureSelfie() {
            const canvas = selfieCanvas;
            const context = canvas.getContext('2d');
            
            canvas.width = selfieVideo.videoWidth;
            canvas.height = selfieVideo.videoHeight;
            
            context.drawImage(selfieVideo, 0, 0);
            
            // Convert to blob
            canvas.toBlob(function(blob) {
                selfieFile = new File([blob], 'selfie.jpg', { type: 'image/jpeg' });
                
                // Show preview
                const dataURL = canvas.toDataURL('image/jpeg');
                selfiePreview.src = dataURL;
                selfiePreview.style.display = 'block';
                
                // Hide video and show retake button
                selfieVideo.style.display = 'none';
                document.getElementById('captureSelfie').style.display = 'none';
                document.getElementById('retakeSelfie').style.display = 'inline-flex';
                
                // Stop camera stream
                if (selfieStream) {
                    selfieStream.getTracks().forEach(track => track.stop());
                }
                
                checkVerificationReadiness();
            }, 'image/jpeg', 0.8);
        }

        function retakeSelfie() {
            selfieFile = null;
            selfiePreview.style.display = 'none';
            document.getElementById('retakeSelfie').style.display = 'none';
            document.getElementById('startSelfieCamera').style.display = 'inline-flex';
            document.querySelector('#selfieContainer .camera-placeholder').style.display = 'block';
            document.querySelector('#selfieContainer .camera-instructions').style.display = 'block';
            checkVerificationReadiness();
        }

        function handleSelfieUpload(event) {
            const file = event.target.files[0];
            if (file) {
                if (validateImageFile(file)) {
                    selfieFile = file;
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        selfiePreview.src = e.target.result;
                        selfiePreview.style.display = 'block';
                        document.querySelector('#selfieContainer .camera-placeholder').style.display = 'none';
                        document.querySelector('#selfieContainer .camera-instructions').style.display = 'none';
                        document.getElementById('retakeSelfie').style.display = 'inline-flex';
                    };
                    reader.readAsDataURL(file);
                    
                    checkVerificationReadiness();
                } else {
                    showAlert('Por favor seleccione un archivo de imagen válido (JPEG, PNG) menor a 5MB', 'error');
                }
            }
        }

        function handleIneUpload(event) {
            const file = event.target.files[0];
            if (file) {
                processIneFile(file);
            }
        }

        function processIneFile(file) {
            if (validateImageFile(file)) {
                ineFile = file;
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    inePreview.src = e.target.result;
                    inePreview.style.display = 'block';
                    document.querySelector('#ineContainer .camera-placeholder').style.display = 'none';
                    document.querySelector('#ineContainer .camera-instructions').style.display = 'none';
                    document.getElementById('removeIne').style.display = 'inline-flex';
                };
                reader.readAsDataURL(file);
                
                checkVerificationReadiness();
            } else {
                showAlert('Por favor seleccione un archivo de imagen válido (JPEG, PNG) menor a 5MB', 'error');
            }
        }

        function removeIne() {
            ineFile = null;
            inePreview.style.display = 'none';
            document.getElementById('removeIne').style.display = 'none';
            document.querySelector('#ineContainer .camera-placeholder').style.display = 'block';
            document.querySelector('#ineContainer .camera-instructions').style.display = 'block';
            document.getElementById('ineUpload').value = '';
            checkVerificationReadiness();
        }

        // Drag and drop handlers
        function handleDragOver(event) {
            event.preventDefault();
            event.currentTarget.classList.add('dragover');
        }

        function handleDragEnter(event) {
            event.preventDefault();
        }

        function handleDragLeave(event) {
            event.currentTarget.classList.remove('dragover');
        }

        function handleDrop(event, type) {
            event.preventDefault();
            event.currentTarget.classList.remove('dragover');
            
            const files = event.dataTransfer.files;
            if (files.length > 0 && type === 'ine') {
                processIneFile(files[0]);
            }
        }

        function validateImageFile(file) {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            const maxSize = 5 * 1024 * 1024; // 5MB
            
            if (!allowedTypes.includes(file.type)) {
                return false;
            }
            
            if (file.size > maxSize) {
                return false;
            }
            
            return true;
        }

        function checkVerificationReadiness() {
            const ready = selfieFile && ineFile;
            startVerificationBtn.disabled = !ready;
            
            if (ready) {
                startVerificationBtn.innerHTML = '<i class="fas fa-shield-check"></i> Iniciar Verificación Facial';
            } else {
                startVerificationBtn.innerHTML = '<i class="fas fa-shield-check"></i> Complete ambas fotografías para continuar';
            }
        }

        async function startFaceVerification() {
            if (!selfieFile || !ineFile) {
                showAlert('Por favor complete ambas fotografías antes de continuar', 'warning');
                return;
            }

            // Show processing overlay
            processingOverlay.classList.remove('hidden');
            
            // Prepare form data
            const formData = new FormData();
            formData.append('selfie', selfieFile);
            formData.append('ine_photo', ineFile);
            
            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch('/face-verification/compare', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });

                const result = await response.json();
                
                // Hide processing overlay
                processingOverlay.classList.add('hidden');
                
                // Show results
                displayVerificationResults(result);
                
            } catch (error) {
                console.error('Verification error:', error);
                processingOverlay.classList.add('hidden');
                showAlert('Error durante la verificación. Por favor intente de nuevo.', 'error');
            }
        }

        function displayVerificationResults(result) {
            const resultsSection = document.getElementById('verificationResults');
            const resultIcon = document.getElementById('resultIcon');
            const resultMessage = document.getElementById('resultMessage');
            const confidenceValue = document.getElementById('confidenceValue');
            const confidenceFill = document.getElementById('confidenceFill');
            
            resultsSection.classList.remove('hidden');
            
            if (result.success && result.data.match) {
                // Success
                resultIcon.innerHTML = '<i class="fas fa-check-circle"></i>';
                resultIcon.className = 'result-icon success';
                resultMessage.textContent = '¡Verificación Exitosa!';
                resultMessage.style.color = '#10b981';
                
                const confidence = result.data.confidence;
                confidenceValue.textContent = confidence;
                confidenceFill.style.width = confidence + '%';
                
                document.getElementById('continueRegistration').style.display = 'inline-flex';
                document.getElementById('retryVerification').style.display = 'none';
                
                showAlert('Su identidad ha sido verificada exitosamente. Puede continuar con el registro.', 'success');
                
            } else {
                // Failure
                resultIcon.innerHTML = '<i class="fas fa-times-circle"></i>';
                resultIcon.className = 'result-icon failure';
                resultMessage.textContent = 'Verificación Fallida';
                resultMessage.style.color = '#ef4444';
                
                const confidence = result.data ? result.data.confidence : 0;
                confidenceValue.textContent = confidence;
                confidenceFill.style.width = confidence + '%';
                
                document.getElementById('continueRegistration').style.display = 'none';
                document.getElementById('retryVerification').style.display = 'inline-flex';
                
                const message = result.message || 'Las fotografías no coinciden. Asegúrese de que ambas imágenes sean claras y correspondan a la misma persona.';
                showAlert(message, 'error');
            }
        }

        function retryVerification() {
            // Reset results
            document.getElementById('verificationResults').classList.add('hidden');
            
            // Allow new verification
            checkVerificationReadiness();
        }

        function continueRegistration() {
            // Get verification results
            const confidence = document.getElementById('confidenceValue').textContent;
            
            // Redirect back to registration with verification confirmed
            const params = new URLSearchParams();
            params.set('face_verified', 'true');
            if (confidence && confidence !== '0') {
                params.set('confidence', confidence);
            }
            
            window.location.href = '/registro?' + params.toString();
        }

        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.innerHTML = `<i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'exclamation'}-circle"></i> ${message}`;
            
            // Insert at top of container
            const container = document.querySelector('.container');
            container.insertBefore(alertDiv, container.firstChild);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 5000);
        }
    </script>
</body>
</html>