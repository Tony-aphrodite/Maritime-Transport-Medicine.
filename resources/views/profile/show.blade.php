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

    /* CURP/RFC Input with Button */
    .input-with-button {
        display: flex;
        gap: 0.75rem;
    }

    .input-with-button .form-control {
        flex: 1;
    }

    .validate-btn {
        padding: 0.75rem 1.25rem;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .validate-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(47, 125, 178, 0.3);
    }

    /* RFC Container */
    .rfc-input-container {
        display: flex;
        border: 2px solid #E5E7EB;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .rfc-input-container:focus-within {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(47, 125, 178, 0.15);
    }

    .rfc-readonly-section {
        background: #F3F4F6;
        padding: 0.75rem 1rem;
        border-right: 2px solid #E5E7EB;
        display: flex;
        align-items: center;
        min-width: 120px;
    }

    .rfc-readonly-text {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        color: var(--primary-dark);
        letter-spacing: 1px;
    }

    .rfc-editable-input {
        border: none !important;
        box-shadow: none !important;
        flex: 1;
        font-family: 'Courier New', monospace;
        font-weight: 600;
        letter-spacing: 1px;
    }

    .rfc-editable-input:focus {
        outline: none;
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
        grid-template-columns: repeat(3, 1fr);
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

    /* Face Verification Section */
    .face-verification-box {
        text-align: center;
        padding: 2rem;
        background: #FAFBFC;
        border-radius: 12px;
        border: 2px dashed #E5E7EB;
    }

    .face-verification-box.verified {
        background: #F0FDF4;
        border-color: #BBF7D0;
        border-style: solid;
    }

    .face-verification-box .icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .face-verification-box.pending .icon { color: var(--warning-color); }
    .face-verification-box.verified .icon { color: var(--success-color); }

    .face-verification-box h3 {
        margin-bottom: 0.5rem;
        color: var(--primary-dark);
    }

    .face-verification-box p {
        color: #6B7280;
        margin-bottom: 1.5rem;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
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

    .btn-success {
        background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
        color: white;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
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

        .input-with-button {
            flex-direction: column;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .rfc-input-container {
            flex-direction: column;
        }

        .rfc-readonly-section {
            border-right: none;
            border-bottom: 2px solid #E5E7EB;
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
                <img src="{{ asset('assets/img/user-avatar.jpg') }}" alt="Avatar" class="profile-avatar">
                <button class="avatar-edit-btn" title="Cambiar foto">
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

                    @if($user->curp_verification_status === 'verified')
                        <span class="badge badge-verified"><i class="fas fa-id-card"></i> CURP Verificado</span>
                    @elseif($user->curp_verification_status === 'pending')
                        <span class="badge badge-pending"><i class="fas fa-clock"></i> CURP Pendiente</span>
                    @endif

                    @if($user->face_verification_status === 'verified')
                        <span class="badge badge-verified"><i class="fas fa-user-check"></i> Identidad Verificada</span>
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

                <div class="verification-card {{ $user->curp_verification_status === 'verified' ? 'verified' : ($user->curp_verification_status === 'pending' ? 'pending' : 'unverified') }}">
                    <div class="verification-icon">
                        <i class="fas {{ $user->curp_verification_status === 'verified' ? 'fa-check-circle' : ($user->curp_verification_status === 'pending' ? 'fa-clock' : 'fa-times-circle') }}"></i>
                    </div>
                    <div class="verification-info">
                        <h4>CURP</h4>
                        <p>{{ $user->curp_verification_status === 'verified' ? 'Verificado' : ($user->curp_verification_status === 'pending' ? 'Pendiente' : 'No verificado') }}</p>
                    </div>
                </div>

                <div class="verification-card {{ $user->face_verification_status === 'verified' ? 'verified' : ($user->face_verification_status === 'pending' ? 'pending' : 'unverified') }}">
                    <div class="verification-icon">
                        <i class="fas {{ $user->face_verification_status === 'verified' ? 'fa-check-circle' : ($user->face_verification_status === 'pending' ? 'fa-clock' : 'fa-times-circle') }}"></i>
                    </div>
                    <div class="verification-info">
                        <h4>Identidad Facial</h4>
                        <p>{{ $user->face_verification_status === 'verified' ? 'Verificado' : ($user->face_verification_status === 'pending' ? 'Pendiente' : 'No verificado') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Profile Form -->
    <form action="{{ route('profile.update') }}" method="POST" id="profileForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="face_verified" id="faceVerifiedInput" value="{{ $user->face_verification_status === 'verified' ? 'true' : '' }}">
        <input type="hidden" name="rfc" id="rfcHiddenInput" value="{{ old('rfc', $user->rfc) }}">

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
                            <select class="form-select" name="nacionalidad" required>
                                <option value="">Seleccione...</option>
                                <option value="mexicana" {{ old('nacionalidad', $user->nacionalidad) == 'mexicana' ? 'selected' : '' }}>Mexicana</option>
                                <option value="estadounidense" {{ old('nacionalidad', $user->nacionalidad) == 'estadounidense' ? 'selected' : '' }}>Estadounidense</option>
                                <option value="canadiense" {{ old('nacionalidad', $user->nacionalidad) == 'canadiense' ? 'selected' : '' }}>Canadiense</option>
                                <option value="otra" {{ old('nacionalidad', $user->nacionalidad) == 'otra' ? 'selected' : '' }}>Otra</option>
                            </select>
                        </div>

                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-id-card"></i>
                                CURP <span class="required">*</span>
                            </label>
                            <div class="input-with-button">
                                <input type="text" class="form-control" id="curpInput" name="curp"
                                       value="{{ old('curp', $user->curp) }}"
                                       placeholder="CURP (18 caracteres)" maxlength="18"
                                       style="text-transform: uppercase; font-family: 'Courier New', monospace; letter-spacing: 0.5px;"
                                       {{ $user->curp_verification_status === 'verified' ? 'disabled' : '' }} required>
                                @if($user->curp_verification_status !== 'verified')
                                <button type="button" onclick="validateCurp()" class="validate-btn">
                                    <i class="fas fa-check-circle"></i> Validar
                                </button>
                                @endif
                            </div>
                            <div id="curpValidationMessage" style="display: none;"></div>
                            @if($user->curp_verification_status === 'verified')
                                <span class="input-hint"><i class="fas fa-lock"></i> CURP verificado, no puede ser modificado</span>
                            @endif
                        </div>
                    </div>

                    <div class="two-columns">
                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-receipt"></i>
                                RFC <span class="required">*</span>
                            </label>
                            <div class="rfc-input-container">
                                <div class="rfc-readonly-section">
                                    <span id="rfcFromCurp" class="rfc-readonly-text">{{ $user->curp ? substr($user->curp, 0, 10) : '--' }}</span>
                                </div>
                                <input type="text" class="form-control rfc-editable-input" name="rfc_suffix" id="rfcSuffixInput"
                                       value="{{ $user->rfc ? substr($user->rfc, 10) : '' }}"
                                       placeholder="XXX" maxlength="3" style="text-transform: uppercase;">
                            </div>
                            <div class="input-hint">
                                <i class="fas fa-info-circle"></i>
                                Los primeros 10 caracteres se toman del CURP. Solo capture los ultimos 3 digitos.
                            </div>
                            <div id="rfcValidationMessage" style="display: none;"></div>
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
                    <div class="two-columns">
                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-map"></i>
                                Estado <span class="required">*</span>
                            </label>
                            <select class="form-select" name="estado" id="estadoSelect" required>
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
                                Municipio / Alcaldia <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" name="municipio"
                                   value="{{ old('municipio', $user->municipio) }}" placeholder="Municipio" required>
                        </div>
                    </div>

                    <div class="two-columns">
                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-map-pin"></i>
                                Colonia / Localidad <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" name="localidad"
                                   value="{{ old('localidad', $user->localidad) }}" placeholder="Colonia" required>
                        </div>

                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-mail-bulk"></i>
                                Codigo Postal <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" name="codigo_postal"
                                   value="{{ old('codigo_postal', $user->codigo_postal) }}" placeholder="12345" maxlength="5" required>
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

        <!-- Section 4 - Parental Consent (for minors) -->
        <div class="form-section" id="parentalConsentSection" style="display: none;">
            <div class="section-header">
                <i class="fas fa-user-shield"></i>
                Seccion 4 - Consentimiento Parental (Menor de 18 anos)
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

        <!-- Section 5 - Face Verification -->
        <div class="form-section">
            <div class="section-header">
                <i class="fas fa-camera"></i>
                Seccion 5 - Verificacion de Identidad Facial
            </div>
            <div class="section-content">
                <div class="face-verification-box {{ $user->face_verification_status === 'verified' ? 'verified' : 'pending' }}" id="faceVerificationBox">
                    @if($user->face_verification_status === 'verified')
                        <i class="fas fa-check-circle icon"></i>
                        <h3>Verificacion Facial Completada</h3>
                        <p>Su identidad ha sido verificada exitosamente mediante reconocimiento facial.</p>
                        <div class="validation-message success" style="display: inline-flex;">
                            <i class="fas fa-shield-check"></i>
                            Estado: Verificado
                        </div>
                    @else
                        <i class="fas fa-exclamation-triangle icon"></i>
                        <h3>Verificacion Facial Requerida</h3>
                        <p>Para completar su perfil, debe verificar su identidad comparando una selfie con la fotografia de su INE/IFE. Este proceso garantiza la seguridad y autenticidad de su cuenta.</p>
                        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                            <button type="button" onclick="startFaceVerification()" class="btn btn-primary">
                                <i class="fas fa-camera"></i> Iniciar Verificacion Facial
                            </button>
                            <button type="button" onclick="simulateFaceVerification()" class="btn btn-secondary">
                                <i class="fas fa-code"></i> Simular (Test)
                            </button>
                        </div>
                    @endif
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
    const curpInput = document.getElementById('curpInput');
    const curpMessage = document.getElementById('curpValidationMessage');
    const birthdateField = document.getElementById('birthdateField');
    const rfcFromCurp = document.getElementById('rfcFromCurp');
    const rfcSuffixInput = document.getElementById('rfcSuffixInput');
    const rfcHiddenInput = document.getElementById('rfcHiddenInput');

    // CURP format validation regex
    const curpRegex = /^[A-Z]{1}[AEIOUX]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01])[HM]{1}[A-Z]{2}[BCDFGHJKLMNPQRSTVWXYZ]{3}[0-9A-Z]{1}[0-9]{1}$/;

    // CURP Input Handler
    if (curpInput && !curpInput.disabled) {
        curpInput.addEventListener('input', function() {
            let value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            this.value = value;

            if (value.length === 0) {
                hideMessage(curpMessage);
                return;
            }

            if (value.length !== 18) {
                showMessage(curpMessage, 'error', '<i class="fas fa-exclamation-circle"></i> CURP debe tener exactamente 18 caracteres');
                return;
            }

            if (!curpRegex.test(value)) {
                showMessage(curpMessage, 'error', '<i class="fas fa-times-circle"></i> Formato de CURP invalido');
                return;
            }

            showMessage(curpMessage, 'success', '<i class="fas fa-check-circle"></i> Formato de CURP valido');
            populateRfcFromCurp(value);
        });
    }

    // Birthdate Handler for Parental Consent
    if (birthdateField) {
        birthdateField.addEventListener('change', checkAgeForParentalConsent);
        // Check on page load if date is already set
        if (birthdateField.value) {
            checkAgeForParentalConsent();
        }
    }

    // RFC Suffix Handler
    if (rfcSuffixInput) {
        rfcSuffixInput.addEventListener('input', updateRfcField);
    }

    // Initialize RFC if CURP exists
    if (curpInput && curpInput.value && curpInput.value.length >= 10) {
        populateRfcFromCurp(curpInput.value);
    }
});

// Show validation message
function showMessage(element, type, html) {
    if (!element) return;
    element.className = 'validation-message ' + type;
    element.innerHTML = html;
    element.style.display = 'flex';
}

// Hide validation message
function hideMessage(element) {
    if (!element) return;
    element.style.display = 'none';
}

// Validate CURP
function validateCurp() {
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

    // Redirect to CURP validation page
    window.location.href = '/curp/validate?from=profile&curp=' + encodeURIComponent(curp);
}

// Populate RFC from CURP
function populateRfcFromCurp(curp) {
    const rfcFromCurp = document.getElementById('rfcFromCurp');
    const rfcSuffixInput = document.getElementById('rfcSuffixInput');

    if (curp && curp.length >= 10) {
        const rfcPrefix = curp.substring(0, 10);
        rfcFromCurp.textContent = rfcPrefix;
        rfcFromCurp.style.color = '#059669';
        rfcSuffixInput.removeAttribute('disabled');
        updateRfcField();
    } else {
        rfcFromCurp.textContent = '--';
        rfcFromCurp.style.color = '#6b7280';
    }
}

// Update RFC hidden field
function updateRfcField() {
    const rfcFromCurp = document.getElementById('rfcFromCurp');
    const rfcSuffixInput = document.getElementById('rfcSuffixInput');
    const rfcHiddenInput = document.getElementById('rfcHiddenInput');
    const rfcMessage = document.getElementById('rfcValidationMessage');

    const rfcPrefix = rfcFromCurp.textContent.trim();
    const rfcSuffix = rfcSuffixInput.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
    rfcSuffixInput.value = rfcSuffix;

    if (rfcPrefix === '--') {
        showMessage(rfcMessage, 'warning', '<i class="fas fa-exclamation-triangle"></i> Primero valide su CURP');
        rfcHiddenInput.value = '';
        return;
    }

    const fullRfc = rfcPrefix + rfcSuffix;
    rfcHiddenInput.value = fullRfc;

    if (rfcSuffix.length === 0) {
        showMessage(rfcMessage, 'info', '<i class="fas fa-info-circle"></i> Ingrese los ultimos 3 caracteres del RFC');
    } else if (rfcSuffix.length < 3) {
        showMessage(rfcMessage, 'warning', '<i class="fas fa-exclamation-triangle"></i> Faltan ' + (3 - rfcSuffix.length) + ' caracteres');
    } else {
        showMessage(rfcMessage, 'success', '<i class="fas fa-check-circle"></i> RFC: ' + fullRfc);
    }
}

// Check age for parental consent
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

// Set parental consent required
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

// Start face verification
function startFaceVerification() {
    const form = document.getElementById('profileForm');
    const formData = new FormData(form);

    // Save form data to session storage
    const data = {};
    formData.forEach((value, key) => { data[key] = value; });
    sessionStorage.setItem('profileFormData', JSON.stringify(data));

    // Redirect to face verification
    window.location.href = '/face-verification?return_to=' + encodeURIComponent(window.location.href);
}

// Simulate face verification (for testing)
function simulateFaceVerification() {
    const faceBox = document.getElementById('faceVerificationBox');
    const faceInput = document.getElementById('faceVerifiedInput');

    faceBox.className = 'face-verification-box verified';
    faceBox.innerHTML = `
        <i class="fas fa-check-circle icon" style="color: #10B981;"></i>
        <h3>Verificacion Facial Completada (Simulada)</h3>
        <p>Su identidad ha sido verificada exitosamente.</p>
        <div class="validation-message success" style="display: inline-flex;">
            <i class="fas fa-shield-check"></i>
            Estado: Verificado (Test Mode)
        </div>
    `;

    faceInput.value = 'true';
    alert('Verificacion facial simulada completada. Ahora puede guardar su perfil.');
}
</script>
@endpush
