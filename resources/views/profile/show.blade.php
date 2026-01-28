@extends('layouts.dashboard')

@section('title', 'Mi Perfil')

@push('styles')
<style>
    :root {
        --primary-color: #2F7DB2;
        --primary-dark: #1A5A8A;
        --primary-light: #5BA4D9;
        --accent-color: #A8D8FF;
        --success-color: #10B981;
        --warning-color: #F59E0B;
        --error-color: #EF4444;
    }

    .profile-container {
        max-width: 100%;
    }

    /* Profile Header Card */
    .profile-header-card {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .profile-header-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        transform: translate(50%, -50%);
    }

    .profile-header-content {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        position: relative;
        z-index: 1;
    }

    .profile-avatar-container {
        position: relative;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(255,255,255,0.3);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .avatar-edit-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        background: white;
        color: var(--primary-color);
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    .avatar-edit-btn:hover {
        background: var(--accent-color);
        transform: scale(1.1);
    }

    .profile-info h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .profile-info .email {
        opacity: 0.9;
        font-size: 0.95rem;
        margin-bottom: 0.75rem;
    }

    .profile-badges {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .badge-verified {
        background: rgba(16, 185, 129, 0.2);
        color: #A7F3D0;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .badge-pending {
        background: rgba(245, 158, 11, 0.2);
        color: #FDE68A;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .badge-unverified {
        background: rgba(239, 68, 68, 0.2);
        color: #FECACA;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    /* Form Section Cards */
    .form-section {
        background: white;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        overflow: hidden;
        border: 1px solid #E5E7EB;
    }

    .section-header {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
        color: white;
        padding: 1rem 1.5rem;
        font-size: 1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-header i {
        font-size: 1.1rem;
    }

    .section-content {
        padding: 1.5rem;
    }

    /* Form Grid */
    .form-grid {
        display: grid;
        gap: 1.25rem;
    }

    .two-columns {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
    }

    .three-columns {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }

    /* Form Groups */
    .field-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .field-label {
        font-weight: 600;
        color: var(--primary-dark);
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .field-label i {
        color: var(--primary-color);
        font-size: 0.9rem;
    }

    .required {
        color: var(--error-color);
        font-weight: 700;
    }

    .form-control, .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #E5E7EB;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #FAFBFC;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: var(--primary-color);
        background: white;
        box-shadow: 0 0 0 3px rgba(47, 125, 178, 0.15);
    }

    .form-control:disabled, .form-select:disabled {
        background: #F3F4F6;
        color: #6B7280;
        cursor: not-allowed;
    }

    .input-hint {
        font-size: 0.8rem;
        color: #6B7280;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .input-hint i {
        font-size: 0.75rem;
    }

    /* Validation Messages */
    .validation-message {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }

    .validation-message.success {
        background: #F0FDF4;
        color: #166534;
        border: 1px solid #BBF7D0;
    }

    .validation-message.warning {
        background: #FFFBEB;
        color: #92400E;
        border: 1px solid #FDE68A;
    }

    .validation-message.error {
        background: #FEF2F2;
        color: #991B1B;
        border: 1px solid #FECACA;
    }

    .validation-message.info {
        background: #EFF6FF;
        color: #1E40AF;
        border: 1px solid #BFDBFE;
    }

    /* Verification Status Cards */
    .verification-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .verification-card {
        padding: 1rem;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
    }

    .verification-card.verified {
        background: #F0FDF4;
        border: 1px solid #BBF7D0;
    }

    .verification-card.pending {
        background: #FFFBEB;
        border: 1px solid #FDE68A;
    }

    .verification-card.unverified {
        background: #FEF2F2;
        border: 1px solid #FECACA;
    }

    .verification-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .verification-card.verified .verification-icon {
        background: #DCFCE7;
        color: #16A34A;
    }

    .verification-card.pending .verification-icon {
        background: #FEF3C7;
        color: #D97706;
    }

    .verification-card.unverified .verification-icon {
        background: #FEE2E2;
        color: #DC2626;
    }

    .verification-info h4 {
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .verification-card.verified .verification-info h4 { color: #166534; }
    .verification-card.pending .verification-info h4 { color: #92400E; }
    .verification-card.unverified .verification-info h4 { color: #991B1B; }

    .verification-info p {
        font-size: 0.8rem;
        color: #6B7280;
        margin: 0;
    }

    /* Document Upload Section */
    .document-upload-area {
        border: 2px dashed #E5E7EB;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        background: #FAFBFC;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .document-upload-area:hover {
        border-color: var(--primary-color);
        background: #F0F7FF;
    }

    .document-upload-area.dragover {
        border-color: var(--primary-color);
        background: #E0F2FE;
    }

    .document-upload-area.has-file {
        border-color: var(--success-color);
        background: #F0FDF4;
        border-style: solid;
    }

    .upload-icon {
        font-size: 2.5rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .document-upload-area.has-file .upload-icon {
        color: var(--success-color);
    }

    .upload-text {
        color: #6B7280;
        margin-bottom: 0.5rem;
    }

    .upload-hint {
        font-size: 0.8rem;
        color: #9CA3AF;
    }

    .file-preview {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: white;
        border-radius: 8px;
        margin-top: 1rem;
        border: 1px solid #E5E7EB;
    }

    .file-preview img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
    }

    .file-info {
        flex: 1;
        text-align: left;
    }

    .file-info .file-name {
        font-weight: 600;
        color: var(--primary-dark);
        word-break: break-all;
    }

    .file-info .file-size {
        font-size: 0.8rem;
        color: #6B7280;
    }

    .remove-file-btn {
        background: #FEE2E2;
        color: #DC2626;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
    }

    .remove-file-btn:hover {
        background: #FECACA;
    }

    /* Parental Consent Section */
    .parental-consent-alert {
        background: #E0F2FE;
        border: 1px solid #0288D1;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .parental-consent-alert i {
        color: #0288D1;
        font-size: 1.25rem;
        margin-top: 2px;
    }

    .parental-consent-alert h4 {
        color: #01579B;
        margin-bottom: 0.25rem;
    }

    .parental-consent-alert p {
        color: #0277BD;
        margin: 0;
        font-size: 0.9rem;
    }

    /* Action Buttons */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #E5E7EB;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(47, 125, 178, 0.4);
    }

    .btn-secondary {
        background: #F3F4F6;
        color: var(--primary-dark);
        border: 2px solid #E5E7EB;
    }

    .btn-secondary:hover {
        background: #E5E7EB;
    }

    /* Alerts */
    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-success {
        background: #F0FDF4;
        color: #166534;
        border: 1px solid #BBF7D0;
    }

    .alert-error {
        background: #FEF2F2;
        color: #991B1B;
        border: 1px solid #FECACA;
    }

    .alert-info {
        background: #EFF6FF;
        color: #1E40AF;
        border: 1px solid #BFDBFE;
    }

    /* Nationality Info Box */
    .nationality-info-box {
        background: #FEF3C7;
        border: 1px solid #F59E0B;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .nationality-info-box i {
        color: #D97706;
        font-size: 1.25rem;
    }

    .nationality-info-box p {
        color: #92400E;
        margin: 0;
        font-size: 0.9rem;
    }

    /* Notification Animations */
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .three-columns {
            grid-template-columns: repeat(2, 1fr);
        }
        .verification-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .profile-header-content {
            flex-direction: column;
            text-align: center;
        }

        .two-columns, .three-columns {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<section class="profile-container">
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            {{ session('info') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <strong>Error en el formulario:</strong>
                <ul style="margin: 0.5rem 0 0 1rem; padding: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Profile Header Card -->
    <div class="profile-header-card">
        <div class="profile-header-content">
            <div class="profile-avatar-container">
                @php
                    $profilePhotoUrl = $user->profile_photo
                        ? asset('storage/' . $user->profile_photo)
                        : asset('assets/img/user-avatar.jpg');
                @endphp
                <img src="{{ $profilePhotoUrl }}" alt="Avatar" class="profile-avatar" id="profileAvatarImg">
                <input type="file" id="profilePhotoInput" accept="image/jpeg,image/png" style="display: none;">
                <button type="button" class="avatar-edit-btn" title="Cambiar foto" onclick="document.getElementById('profilePhotoInput').click()">
                    <i class="fas fa-camera"></i>
                </button>
            </div>
            <div class="profile-info">
                <h2>{{ $user->full_name ?? $user->name ?? 'Usuario' }}</h2>
                <p class="email"><i class="fas fa-envelope"></i> {{ $user->email }}</p>
                <div class="profile-badges">
                    @if($user->hasVerifiedEmail())
                        <span class="badge badge-verified"><i class="fas fa-check"></i> Email Verificado</span>
                    @else
                        <span class="badge badge-unverified"><i class="fas fa-times"></i> Email No Verificado</span>
                    @endif

                    @if($user->document_path)
                        <span class="badge badge-verified"><i class="fas fa-id-card"></i> Documento Subido</span>
                    @else
                        <span class="badge badge-pending"><i class="fas fa-clock"></i> Documento Pendiente</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Verification Status Section -->
    <div class="form-section">
        <div class="section-header">
            <i class="fas fa-shield-alt"></i>
            Estado de Verificacion
        </div>
        <div class="section-content">
            <div class="verification-grid">
                <div class="verification-card {{ $user->hasVerifiedEmail() ? 'verified' : 'unverified' }}">
                    <div class="verification-icon">
                        <i class="fas {{ $user->hasVerifiedEmail() ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                    </div>
                    <div class="verification-info">
                        <h4>Email</h4>
                        <p>{{ $user->hasVerifiedEmail() ? 'Verificado' : 'No verificado' }}</p>
                    </div>
                </div>

                <div class="verification-card {{ $user->document_path ? 'verified' : 'unverified' }}">
                    <div class="verification-icon">
                        <i class="fas {{ $user->document_path ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                    </div>
                    <div class="verification-info">
                        <h4>Documento de Identidad</h4>
                        <p>{{ $user->document_path ? 'Subido' : 'Pendiente' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Profile Form -->
    <form action="{{ route('profile.update') }}" method="POST" id="profileForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Section 1 - General Information -->
        <div class="form-section">
            <div class="section-header">
                <i class="fas fa-user-circle"></i>
                Seccion 1 - Informacion General
            </div>
            <div class="section-content">
                <div class="form-grid">
                    <div class="three-columns">
                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-user"></i>
                                Nombre(s) <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" name="nombres" id="nombres"
                                   value="{{ old('nombres', $user->nombres) }}" placeholder="Nombre(s)" required>
                        </div>

                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-user"></i>
                                Apellido Paterno <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" name="apellido_paterno"
                                   value="{{ old('apellido_paterno', $user->apellido_paterno) }}" placeholder="Apellido Paterno" required>
                        </div>

                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-user"></i>
                                Apellido Materno
                            </label>
                            <input type="text" class="form-control" name="apellido_materno"
                                   value="{{ old('apellido_materno', $user->apellido_materno) }}" placeholder="Apellido Materno">
                        </div>
                    </div>

                    <div class="two-columns">
                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-flag"></i>
                                Nacionalidad <span class="required">*</span>
                            </label>
                            <select class="form-select" name="nacionalidad" id="nacionalidadSelect" required>
                                <option value="">Seleccione...</option>
                                <option value="mexicana" {{ old('nacionalidad', $user->nacionalidad) == 'mexicana' ? 'selected' : '' }}>Mexicana</option>
                                <option value="estadounidense" {{ old('nacionalidad', $user->nacionalidad) == 'estadounidense' ? 'selected' : '' }}>Estadounidense</option>
                                <option value="canadiense" {{ old('nacionalidad', $user->nacionalidad) == 'canadiense' ? 'selected' : '' }}>Canadiense</option>
                                <option value="otra" {{ old('nacionalidad', $user->nacionalidad) == 'otra' ? 'selected' : '' }}>Otra</option>
                            </select>
                        </div>

                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-envelope"></i>
                                Correo Electronico
                            </label>
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                            <span class="input-hint"><i class="fas fa-lock"></i> El correo no puede ser modificado</span>
                        </div>
                    </div>

                    <!-- Mexican-only fields (CURP and RFC) -->
                    <div id="mexicanFieldsSection" style="display: none;">
                        <div class="two-columns">
                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-id-card"></i>
                                    CURP <span class="required" id="curpRequired">*</span>
                                </label>
                                <input type="text" class="form-control" id="curpInput" name="curp"
                                       value="{{ old('curp', $user->curp) }}"
                                       placeholder="CURP (18 caracteres)" maxlength="18"
                                       style="text-transform: uppercase; font-family: 'Courier New', monospace; letter-spacing: 0.5px;">
                                <div id="curpValidationMessage" style="display: none;"></div>
                            </div>

                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-receipt"></i>
                                    RFC <span class="required" id="rfcRequired">*</span>
                                </label>
                                <input type="text" class="form-control" name="rfc" id="rfcInput"
                                       value="{{ old('rfc', $user->rfc) }}"
                                       placeholder="RFC (13 caracteres)" maxlength="13"
                                       style="text-transform: uppercase; font-family: 'Courier New', monospace; letter-spacing: 0.5px;">
                                <div id="rfcValidationMessage" style="display: none;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2 - Personal Data -->
        <div class="form-section">
            <div class="section-header">
                <i class="fas fa-user-edit"></i>
                Seccion 2 - Datos Personales
            </div>
            <div class="section-content">
                <div class="form-grid">
                    <div class="three-columns">
                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-venus-mars"></i>
                                Sexo <span class="required">*</span>
                            </label>
                            <select class="form-select" name="sexo" required>
                                <option value="">Seleccione...</option>
                                <option value="M" {{ old('sexo', $user->sexo) == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ old('sexo', $user->sexo) == 'F' ? 'selected' : '' }}>Femenino</option>
                            </select>
                        </div>

                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-calendar-alt"></i>
                                Fecha de Nacimiento <span class="required">*</span>
                            </label>
                            <input type="date" class="form-control" name="fecha_nacimiento" id="birthdateField"
                                   value="{{ old('fecha_nacimiento', $user->fecha_nacimiento ? $user->fecha_nacimiento->format('Y-m-d') : '') }}" required>
                            <div id="ageVerificationMessage" style="display: none;"></div>
                        </div>

                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-globe-americas"></i>
                                Pais de Nacimiento <span class="required">*</span>
                            </label>
                            <select class="form-select" name="pais_nacimiento" required>
                                <option value="">Seleccione...</option>
                                <option value="Mexico" {{ old('pais_nacimiento', $user->pais_nacimiento) == 'Mexico' ? 'selected' : '' }}>Mexico</option>
                                <option value="USA" {{ old('pais_nacimiento', $user->pais_nacimiento) == 'USA' ? 'selected' : '' }}>Estados Unidos</option>
                                <option value="Canada" {{ old('pais_nacimiento', $user->pais_nacimiento) == 'Canada' ? 'selected' : '' }}>Canada</option>
                                <option value="Otro" {{ old('pais_nacimiento', $user->pais_nacimiento) == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>
                    </div>

                    <div class="two-columns">
                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-phone"></i>
                                Telefono Casa
                            </label>
                            <input type="tel" class="form-control" name="telefono_casa"
                                   value="{{ old('telefono_casa', $user->telefono_casa) }}" placeholder="(55) 1234-5678">
                        </div>

                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-mobile-alt"></i>
                                Telefono Movil <span class="required">*</span>
                            </label>
                            <input type="tel" class="form-control" name="telefono_movil"
                                   value="{{ old('telefono_movil', $user->telefono_movil) }}" placeholder="(55) 1234-5678" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3 - Address -->
        <div class="form-section">
            <div class="section-header">
                <i class="fas fa-home"></i>
                Seccion 3 - Domicilio
            </div>
            <div class="section-content">
                <div class="form-grid">
                    <!-- Mexican Address Fields -->
                    <div id="mexicanAddressSection">
                        <div class="two-columns">
                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-map"></i>
                                    Estado <span class="required">*</span>
                                </label>
                                <select class="form-select" name="estado" id="estadoSelect">
                                    <option value="">Seleccione un estado...</option>
                                    <option value="Aguascalientes" {{ old('estado', $user->estado) == 'Aguascalientes' ? 'selected' : '' }}>Aguascalientes</option>
                                    <option value="Baja California" {{ old('estado', $user->estado) == 'Baja California' ? 'selected' : '' }}>Baja California</option>
                                    <option value="Baja California Sur" {{ old('estado', $user->estado) == 'Baja California Sur' ? 'selected' : '' }}>Baja California Sur</option>
                                    <option value="Campeche" {{ old('estado', $user->estado) == 'Campeche' ? 'selected' : '' }}>Campeche</option>
                                    <option value="Chiapas" {{ old('estado', $user->estado) == 'Chiapas' ? 'selected' : '' }}>Chiapas</option>
                                    <option value="Chihuahua" {{ old('estado', $user->estado) == 'Chihuahua' ? 'selected' : '' }}>Chihuahua</option>
                                    <option value="Ciudad de Mexico" {{ old('estado', $user->estado) == 'Ciudad de Mexico' ? 'selected' : '' }}>Ciudad de Mexico</option>
                                    <option value="Coahuila" {{ old('estado', $user->estado) == 'Coahuila' ? 'selected' : '' }}>Coahuila</option>
                                    <option value="Colima" {{ old('estado', $user->estado) == 'Colima' ? 'selected' : '' }}>Colima</option>
                                    <option value="Durango" {{ old('estado', $user->estado) == 'Durango' ? 'selected' : '' }}>Durango</option>
                                    <option value="Estado de Mexico" {{ old('estado', $user->estado) == 'Estado de Mexico' ? 'selected' : '' }}>Estado de Mexico</option>
                                    <option value="Guanajuato" {{ old('estado', $user->estado) == 'Guanajuato' ? 'selected' : '' }}>Guanajuato</option>
                                    <option value="Guerrero" {{ old('estado', $user->estado) == 'Guerrero' ? 'selected' : '' }}>Guerrero</option>
                                    <option value="Hidalgo" {{ old('estado', $user->estado) == 'Hidalgo' ? 'selected' : '' }}>Hidalgo</option>
                                    <option value="Jalisco" {{ old('estado', $user->estado) == 'Jalisco' ? 'selected' : '' }}>Jalisco</option>
                                    <option value="Michoacan" {{ old('estado', $user->estado) == 'Michoacan' ? 'selected' : '' }}>Michoacan</option>
                                    <option value="Morelos" {{ old('estado', $user->estado) == 'Morelos' ? 'selected' : '' }}>Morelos</option>
                                    <option value="Nayarit" {{ old('estado', $user->estado) == 'Nayarit' ? 'selected' : '' }}>Nayarit</option>
                                    <option value="Nuevo Leon" {{ old('estado', $user->estado) == 'Nuevo Leon' ? 'selected' : '' }}>Nuevo Leon</option>
                                    <option value="Oaxaca" {{ old('estado', $user->estado) == 'Oaxaca' ? 'selected' : '' }}>Oaxaca</option>
                                    <option value="Puebla" {{ old('estado', $user->estado) == 'Puebla' ? 'selected' : '' }}>Puebla</option>
                                    <option value="Queretaro" {{ old('estado', $user->estado) == 'Queretaro' ? 'selected' : '' }}>Queretaro</option>
                                    <option value="Quintana Roo" {{ old('estado', $user->estado) == 'Quintana Roo' ? 'selected' : '' }}>Quintana Roo</option>
                                    <option value="San Luis Potosi" {{ old('estado', $user->estado) == 'San Luis Potosi' ? 'selected' : '' }}>San Luis Potosi</option>
                                    <option value="Sinaloa" {{ old('estado', $user->estado) == 'Sinaloa' ? 'selected' : '' }}>Sinaloa</option>
                                    <option value="Sonora" {{ old('estado', $user->estado) == 'Sonora' ? 'selected' : '' }}>Sonora</option>
                                    <option value="Tabasco" {{ old('estado', $user->estado) == 'Tabasco' ? 'selected' : '' }}>Tabasco</option>
                                    <option value="Tamaulipas" {{ old('estado', $user->estado) == 'Tamaulipas' ? 'selected' : '' }}>Tamaulipas</option>
                                    <option value="Tlaxcala" {{ old('estado', $user->estado) == 'Tlaxcala' ? 'selected' : '' }}>Tlaxcala</option>
                                    <option value="Veracruz" {{ old('estado', $user->estado) == 'Veracruz' ? 'selected' : '' }}>Veracruz</option>
                                    <option value="Yucatan" {{ old('estado', $user->estado) == 'Yucatan' ? 'selected' : '' }}>Yucatan</option>
                                    <option value="Zacatecas" {{ old('estado', $user->estado) == 'Zacatecas' ? 'selected' : '' }}>Zacatecas</option>
                                </select>
                            </div>

                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-city"></i>
                                    Ciudad <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" name="municipio" id="municipioInput"
                                       value="{{ old('municipio', $user->municipio) }}" placeholder="Ciudad">
                            </div>
                        </div>
                    </div>

                    <!-- Non-Mexican Address Fields -->
                    <div id="foreignAddressSection" style="display: none;">
                        <div class="nationality-info-box">
                            <i class="fas fa-info-circle"></i>
                            <p>Como usuario extranjero, por favor ingrese su direccion manualmente.</p>
                        </div>
                        <div class="two-columns">
                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-map"></i>
                                    Estado / Provincia <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" name="estado_foreign" id="estadoForeignInput"
                                       value="{{ old('estado_foreign', $user->estado) }}" placeholder="Estado o Provincia">
                            </div>

                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-city"></i>
                                    Ciudad <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" name="municipio_foreign" id="municipioForeignInput"
                                       value="{{ old('municipio_foreign', $user->municipio) }}" placeholder="Ciudad">
                            </div>
                        </div>
                    </div>

                    <div class="two-columns">
                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-map-pin"></i>
                                Colonia / Localidad
                            </label>
                            <input type="text" class="form-control" name="localidad"
                                   value="{{ old('localidad', $user->localidad) }}" placeholder="Colonia (Opcional)">
                        </div>

                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-mail-bulk"></i>
                                Codigo Postal <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" name="codigo_postal"
                                   value="{{ old('codigo_postal', $user->codigo_postal) }}" placeholder="12345" maxlength="10" required>
                        </div>
                    </div>

                    <div class="field-group">
                        <label class="field-label">
                            <i class="fas fa-road"></i>
                            Calle <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control" name="calle"
                               value="{{ old('calle', $user->calle) }}" placeholder="Nombre de la calle" required>
                    </div>

                    <div class="two-columns">
                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-hashtag"></i>
                                Numero Exterior <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" name="numero_exterior"
                                   value="{{ old('numero_exterior', $user->numero_exterior) }}" placeholder="123" required>
                        </div>

                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-hashtag"></i>
                                Numero Interior
                            </label>
                            <input type="text" class="form-control" name="numero_interior"
                                   value="{{ old('numero_interior', $user->numero_interior) }}" placeholder="Depto. 4B">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 4 - Document Upload -->
        <div class="form-section">
            <div class="section-header">
                <i class="fas fa-id-card"></i>
                Seccion 4 - Documento de Identidad
            </div>
            <div class="section-content">
                <!-- Mexican Document (INE) -->
                <div id="ineUploadSection">
                    <p style="margin-bottom: 1rem; color: #6B7280;">
                        <i class="fas fa-info-circle" style="color: var(--primary-color);"></i>
                        Por favor suba una imagen clara de su INE/IFE (ambos lados si es posible).
                    </p>
                    <div class="document-upload-area" id="ineDropArea" onclick="document.getElementById('ineInput').click()">
                        <input type="file" id="ineInput" name="ine_document" accept="image/*,.pdf" style="display: none;">
                        <i class="fas fa-id-card upload-icon"></i>
                        <p class="upload-text">Haga clic o arrastre su INE/IFE aqui</p>
                        <p class="upload-hint">Formatos: JPG, PNG, PDF (Max. 5MB)</p>
                    </div>
                    <div id="inePreview" style="display: none;"></div>
                    @if($user->document_path && $user->document_type === 'ine')
                        <div class="file-preview" style="margin-top: 1rem;">
                            <i class="fas fa-file-image" style="font-size: 2rem; color: var(--success-color);"></i>
                            <div class="file-info">
                                <p class="file-name">Documento INE subido</p>
                                <p class="file-size">Archivo almacenado correctamente</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Foreign Document (Passport) -->
                <div id="passportUploadSection" style="display: none;">
                    <p style="margin-bottom: 1rem; color: #6B7280;">
                        <i class="fas fa-info-circle" style="color: var(--primary-color);"></i>
                        Por favor suba una imagen clara de la pagina principal de su pasaporte.
                    </p>
                    <div class="document-upload-area" id="passportDropArea" onclick="document.getElementById('passportInput').click()">
                        <input type="file" id="passportInput" name="passport_document" accept="image/*,.pdf" style="display: none;">
                        <i class="fas fa-passport upload-icon"></i>
                        <p class="upload-text">Haga clic o arrastre su Pasaporte aqui</p>
                        <p class="upload-hint">Formatos: JPG, PNG, PDF (Max. 5MB)</p>
                    </div>
                    <div id="passportPreview" style="display: none;"></div>
                    @if($user->document_path && $user->document_type === 'passport')
                        <div class="file-preview" style="margin-top: 1rem;">
                            <i class="fas fa-file-image" style="font-size: 2rem; color: var(--success-color);"></i>
                            <div class="file-info">
                                <p class="file-name">Pasaporte subido</p>
                                <p class="file-size">Archivo almacenado correctamente</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Section 5 - Parental Consent (for minors) -->
        <div class="form-section" id="parentalConsentSection" style="display: none;">
            <div class="section-header">
                <i class="fas fa-user-shield"></i>
                Seccion 5 - Consentimiento Parental (Menor de 18 anos)
            </div>
            <div class="section-content">
                <div class="parental-consent-alert">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <h4>Consentimiento Parental Requerido</h4>
                        <p>Como eres menor de 18 anos, necesitamos el consentimiento de tu padre, madre o tutor legal para completar tu registro.</p>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="two-columns">
                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-user"></i>
                                Nombre del Padre/Madre/Tutor <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" name="parent_full_name" id="parentFullNameField"
                                   placeholder="Nombre completo">
                        </div>

                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-envelope"></i>
                                Correo del Padre/Madre/Tutor <span class="required">*</span>
                            </label>
                            <input type="email" class="form-control" name="parent_email" id="parentEmailField"
                                   placeholder="correo@ejemplo.com">
                        </div>
                    </div>

                    <div class="two-columns">
                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-phone"></i>
                                Telefono del Padre/Madre/Tutor
                            </label>
                            <input type="tel" class="form-control" name="parent_phone" id="parentPhoneField"
                                   placeholder="(555) 123-4567">
                        </div>

                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-users"></i>
                                Relacion <span class="required">*</span>
                            </label>
                            <select class="form-select" name="parent_relationship" id="parentRelationshipField">
                                <option value="">Seleccione...</option>
                                <option value="padre">Padre</option>
                                <option value="madre">Madre</option>
                                <option value="tutor_legal">Tutor Legal</option>
                                <option value="abuelo">Abuelo/a</option>
                                <option value="otro">Otro Familiar</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="window.location.reload()">
                <i class="fas fa-undo"></i> Cancelar
            </button>
            <button type="submit" class="btn btn-primary" id="submitBtn">
                <i class="fas fa-save"></i> Guardar Cambios
            </button>
        </div>
    </form>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nacionalidadSelect = document.getElementById('nacionalidadSelect');
    const birthdateField = document.getElementById('birthdateField');
    const curpInput = document.getElementById('curpInput');
    const rfcInput = document.getElementById('rfcInput');

    // Initialize nationality-based display
    handleNationalityChange();
    nacionalidadSelect.addEventListener('change', handleNationalityChange);

    // Birthdate Handler for Parental Consent
    if (birthdateField) {
        birthdateField.addEventListener('change', checkAgeForParentalConsent);
        if (birthdateField.value) {
            checkAgeForParentalConsent();
        }
    }

    // CURP format validation
    if (curpInput) {
        curpInput.addEventListener('input', function() {
            let value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            this.value = value;
            validateCurpFormat(value);
        });
    }

    // RFC format validation
    if (rfcInput) {
        rfcInput.addEventListener('input', function() {
            let value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            this.value = value;
            validateRfcFormat(value);
        });
    }

    // File upload handlers
    setupFileUpload('ineInput', 'ineDropArea', 'inePreview');
    setupFileUpload('passportInput', 'passportDropArea', 'passportPreview');

    // Profile photo upload handler
    const profilePhotoInput = document.getElementById('profilePhotoInput');
    if (profilePhotoInput) {
        profilePhotoInput.addEventListener('change', handleProfilePhotoUpload);
    }
});

function handleProfilePhotoUpload(event) {
    const file = event.target.files[0];
    if (!file) return;

    // Validate file size (2MB max)
    if (file.size > 2 * 1024 * 1024) {
        alert('La imagen es demasiado grande. Maximo 2MB.');
        event.target.value = '';
        return;
    }

    // Validate file type
    const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    if (!validTypes.includes(file.type)) {
        alert('Tipo de archivo no valido. Use JPG o PNG.');
        event.target.value = '';
        return;
    }

    // Show loading state
    const avatarImg = document.getElementById('profileAvatarImg');
    const avatarBtn = document.querySelector('.avatar-edit-btn');
    const originalContent = avatarBtn.innerHTML;
    avatarBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    avatarBtn.disabled = true;

    // Create FormData and upload
    const formData = new FormData();
    formData.append('profile_photo', file);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('{{ route("profile.photo.update") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update avatar image on profile page
            avatarImg.src = data.photo_url;
            // Also update dashboard header avatar if exists
            const dashboardAvatar = document.getElementById('dashboardAvatar');
            if (dashboardAvatar) {
                dashboardAvatar.src = data.photo_url;
            }
            // Show success message
            showNotification('success', data.message);
        } else {
            showNotification('error', data.message || 'Error al subir la foto.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Error al subir la foto. Por favor intente de nuevo.');
    })
    .finally(() => {
        // Restore button state
        avatarBtn.innerHTML = originalContent;
        avatarBtn.disabled = false;
        event.target.value = '';
    });
}

function showNotification(type, message) {
    // Remove existing notifications
    const existingNotif = document.querySelector('.photo-notification');
    if (existingNotif) existingNotif.remove();

    // Create notification
    const notif = document.createElement('div');
    notif.className = 'photo-notification alert alert-' + type;
    notif.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; animation: slideIn 0.3s ease;';
    notif.innerHTML = `
        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
        ${message}
    `;
    document.body.appendChild(notif);

    // Auto remove after 3 seconds
    setTimeout(() => {
        notif.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notif.remove(), 300);
    }, 3000);
}

function handleNationalityChange() {
    const nacionalidad = document.getElementById('nacionalidadSelect').value;
    const isMexican = nacionalidad === 'mexicana';

    // Toggle Mexican-only fields (CURP, RFC)
    const mexicanFields = document.getElementById('mexicanFieldsSection');
    const curpInput = document.getElementById('curpInput');
    const rfcInput = document.getElementById('rfcInput');

    if (isMexican) {
        mexicanFields.style.display = 'block';
        curpInput.setAttribute('required', 'required');
        rfcInput.setAttribute('required', 'required');
    } else {
        mexicanFields.style.display = 'none';
        curpInput.removeAttribute('required');
        rfcInput.removeAttribute('required');
        curpInput.value = '';
        rfcInput.value = '';
    }

    // Toggle address sections
    const mexicanAddress = document.getElementById('mexicanAddressSection');
    const foreignAddress = document.getElementById('foreignAddressSection');
    const estadoSelect = document.getElementById('estadoSelect');
    const municipioInput = document.getElementById('municipioInput');
    const estadoForeign = document.getElementById('estadoForeignInput');
    const municipioForeign = document.getElementById('municipioForeignInput');

    if (isMexican) {
        mexicanAddress.style.display = 'block';
        foreignAddress.style.display = 'none';
        estadoSelect.setAttribute('required', 'required');
        municipioInput.setAttribute('required', 'required');
        estadoForeign.removeAttribute('required');
        municipioForeign.removeAttribute('required');
    } else {
        mexicanAddress.style.display = 'none';
        foreignAddress.style.display = 'block';
        estadoSelect.removeAttribute('required');
        municipioInput.removeAttribute('required');
        estadoForeign.setAttribute('required', 'required');
        municipioForeign.setAttribute('required', 'required');
    }

    // Toggle document upload sections
    const ineSection = document.getElementById('ineUploadSection');
    const passportSection = document.getElementById('passportUploadSection');

    if (isMexican) {
        ineSection.style.display = 'block';
        passportSection.style.display = 'none';
    } else {
        ineSection.style.display = 'none';
        passportSection.style.display = 'block';
    }
}

function validateCurpFormat(curp) {
    const curpMessage = document.getElementById('curpValidationMessage');
    const curpRegex = /^[A-Z]{1}[AEIOUX]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01])[HM]{1}[A-Z]{2}[BCDFGHJKLMNPQRSTVWXYZ]{3}[0-9A-Z]{1}[0-9]{1}$/;

    if (curp.length === 0) {
        hideMessage(curpMessage);
        return;
    }

    if (curp.length !== 18) {
        showMessage(curpMessage, 'warning', '<i class="fas fa-exclamation-circle"></i> CURP debe tener 18 caracteres (' + curp.length + '/18)');
        return;
    }

    if (!curpRegex.test(curp)) {
        showMessage(curpMessage, 'error', '<i class="fas fa-times-circle"></i> Formato de CURP invalido');
        return;
    }

    showMessage(curpMessage, 'success', '<i class="fas fa-check-circle"></i> Formato de CURP valido');
}

function validateRfcFormat(rfc) {
    const rfcMessage = document.getElementById('rfcValidationMessage');
    const rfcRegex = /^[A-Z]{3,4}[0-9]{6}[A-Z0-9]{3}$/;

    if (rfc.length === 0) {
        hideMessage(rfcMessage);
        return;
    }

    if (rfc.length < 12 || rfc.length > 13) {
        showMessage(rfcMessage, 'warning', '<i class="fas fa-exclamation-circle"></i> RFC debe tener 12 o 13 caracteres (' + rfc.length + ')');
        return;
    }

    if (!rfcRegex.test(rfc)) {
        showMessage(rfcMessage, 'error', '<i class="fas fa-times-circle"></i> Formato de RFC invalido');
        return;
    }

    showMessage(rfcMessage, 'success', '<i class="fas fa-check-circle"></i> Formato de RFC valido');
}

function showMessage(element, type, html) {
    if (!element) return;
    element.className = 'validation-message ' + type;
    element.innerHTML = html;
    element.style.display = 'flex';
}

function hideMessage(element) {
    if (!element) return;
    element.style.display = 'none';
}

function checkAgeForParentalConsent() {
    const birthdateField = document.getElementById('birthdateField');
    const ageMessage = document.getElementById('ageVerificationMessage');
    const parentalSection = document.getElementById('parentalConsentSection');

    if (!birthdateField.value) {
        hideMessage(ageMessage);
        parentalSection.style.display = 'none';
        setParentalConsentRequired(false);
        return;
    }

    const birthDate = new Date(birthdateField.value);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();

    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }

    if (age < 18) {
        showMessage(ageMessage, 'warning', '<i class="fas fa-exclamation-triangle"></i> Menor de edad (' + age + ' anos). Se requiere consentimiento parental.');
        parentalSection.style.display = 'block';
        setParentalConsentRequired(true);
        setTimeout(() => {
            parentalSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 100);
    } else {
        showMessage(ageMessage, 'success', '<i class="fas fa-check-circle"></i> Mayor de edad (' + age + ' anos)');
        parentalSection.style.display = 'none';
        setParentalConsentRequired(false);
        setTimeout(() => hideMessage(ageMessage), 3000);
    }
}

function setParentalConsentRequired(required) {
    const fields = ['parentFullNameField', 'parentEmailField', 'parentRelationshipField'];
    fields.forEach(id => {
        const field = document.getElementById(id);
        if (field) {
            if (required) {
                field.setAttribute('required', 'required');
            } else {
                field.removeAttribute('required');
                field.value = '';
            }
        }
    });
}

function setupFileUpload(inputId, dropAreaId, previewId) {
    const input = document.getElementById(inputId);
    const dropArea = document.getElementById(dropAreaId);
    const preview = document.getElementById(previewId);

    if (!input || !dropArea) return;

    // Drag and drop events
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, () => dropArea.classList.add('dragover'), false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, () => dropArea.classList.remove('dragover'), false);
    });

    dropArea.addEventListener('drop', (e) => {
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            input.files = files;
            handleFileSelect(input, dropArea, preview);
        }
    });

    input.addEventListener('change', () => handleFileSelect(input, dropArea, preview));
}

function handleFileSelect(input, dropArea, preview) {
    const file = input.files[0];
    if (!file) return;

    // Validate file size (5MB max)
    if (file.size > 5 * 1024 * 1024) {
        alert('El archivo es demasiado grande. Maximo 5MB.');
        input.value = '';
        return;
    }

    // Validate file type
    const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
    if (!validTypes.includes(file.type)) {
        alert('Tipo de archivo no valido. Use JPG, PNG o PDF.');
        input.value = '';
        return;
    }

    dropArea.classList.add('has-file');

    // Show preview
    preview.style.display = 'block';
    preview.innerHTML = `
        <div class="file-preview">
            ${file.type.startsWith('image/') ?
                `<img src="${URL.createObjectURL(file)}" alt="Preview">` :
                `<i class="fas fa-file-pdf" style="font-size: 2rem; color: #DC2626;"></i>`
            }
            <div class="file-info">
                <p class="file-name">${file.name}</p>
                <p class="file-size">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
            </div>
            <button type="button" class="remove-file-btn" onclick="removeFile('${input.id}', '${dropArea.id}', '${preview.id}')">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
}

function removeFile(inputId, dropAreaId, previewId) {
    const input = document.getElementById(inputId);
    const dropArea = document.getElementById(dropAreaId);
    const preview = document.getElementById(previewId);

    input.value = '';
    dropArea.classList.remove('has-file');
    preview.style.display = 'none';
    preview.innerHTML = '';
}
</script>
@endpush
