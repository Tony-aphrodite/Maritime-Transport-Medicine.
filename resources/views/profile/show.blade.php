@extends('layouts.dashboard')

@section('title', 'Mi Perfil')

@push('styles')
<style>
    .profile-section {
        background: white;
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }

    .profile-header {
        display: flex;
        align-items: center;
        gap: 30px;
        padding-bottom: 30px;
        border-bottom: 1px solid #eef2f6;
        margin-bottom: 30px;
    }

    .profile-avatar-container {
        position: relative;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--primary-light);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .avatar-edit-btn {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: var(--primary-dark);
        color: white;
        border: none;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
    }

    .avatar-edit-btn:hover {
        background: var(--primary-light);
    }

    .profile-info h2 {
        color: var(--primary-dark);
        margin-bottom: 5px;
        font-size: 1.8rem;
    }

    .profile-info p {
        color: #666;
        margin-bottom: 10px;
    }

    .profile-badges {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .badge-verified {
        background: #dcfce7;
        color: #166534;
    }

    .badge-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-unverified {
        background: #fee2e2;
        color: #991b1b;
    }

    .section-title {
        font-size: 1.2rem;
        color: var(--primary-dark);
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: var(--primary-light);
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-group.full-width {
        grid-column: span 2;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: var(--primary-dark);
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 10px;
        font-size: 1rem;
        transition: 0.3s;
        background: #f8fafc;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary-light);
        background: white;
        box-shadow: 0 0 0 3px rgba(47, 125, 178, 0.1);
    }

    .form-group input:disabled,
    .form-group select:disabled {
        background: #e9ecef;
        cursor: not-allowed;
    }

    .form-group .input-hint {
        font-size: 0.8rem;
        color: #888;
        margin-top: 5px;
    }

    .btn-container {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eef2f6;
    }

    .btn {
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: var(--primary-dark);
        color: white;
    }

    .btn-primary:hover {
        background: var(--primary-light);
        transform: translateY(-2px);
    }

    .btn-secondary {
        background: #f1f5f9;
        color: var(--primary-dark);
    }

    .btn-secondary:hover {
        background: #e2e8f0;
    }

    .verification-status {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 15px;
    }

    .verification-status.verified {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
    }

    .verification-status.pending {
        background: #fffbeb;
        border: 1px solid #fde68a;
    }

    .verification-status.unverified {
        background: #fef2f2;
        border: 1px solid #fecaca;
    }

    .verification-status i {
        font-size: 1.5rem;
    }

    .verification-status.verified i {
        color: #16a34a;
    }

    .verification-status.pending i {
        color: #d97706;
    }

    .verification-status.unverified i {
        color: #dc2626;
    }

    .verification-info h4 {
        font-size: 1rem;
        margin-bottom: 3px;
    }

    .verification-info p {
        font-size: 0.85rem;
        color: #666;
        margin: 0;
    }

    .alert {
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
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

    @media (max-width: 768px) {
        .profile-header {
            flex-direction: column;
            text-align: center;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: span 1;
        }

        .btn-container {
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
<section class="profile-page">
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

    <!-- Profile Header -->
    <div class="profile-section">
        <div class="profile-header">
            <div class="profile-avatar-container">
                <img src="{{ asset('assets/img/user-avatar.jpg') }}" alt="Avatar" class="profile-avatar">
                <button class="avatar-edit-btn" title="Cambiar foto">
                    <i class="fas fa-camera"></i>
                </button>
            </div>
            <div class="profile-info">
                <h2>{{ $user->full_name ?? $user->name ?? 'Usuario' }}</h2>
                <p><i class="fas fa-envelope"></i> {{ $user->email }}</p>
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

        <!-- Verification Status -->
        <h3 class="section-title"><i class="fas fa-shield-alt"></i> Estado de Verificacion</h3>

        <div class="verification-status {{ $user->hasVerifiedEmail() ? 'verified' : 'unverified' }}">
            <i class="fas {{ $user->hasVerifiedEmail() ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
            <div class="verification-info">
                <h4>Verificacion de Email</h4>
                <p>{{ $user->hasVerifiedEmail() ? 'Tu correo electronico ha sido verificado.' : 'Tu correo electronico aun no ha sido verificado.' }}</p>
            </div>
        </div>

        <div class="verification-status {{ $user->curp_verification_status === 'verified' ? 'verified' : ($user->curp_verification_status === 'pending' ? 'pending' : 'unverified') }}">
            <i class="fas {{ $user->curp_verification_status === 'verified' ? 'fa-check-circle' : ($user->curp_verification_status === 'pending' ? 'fa-clock' : 'fa-times-circle') }}"></i>
            <div class="verification-info">
                <h4>Verificacion de CURP</h4>
                <p>
                    @if($user->curp_verification_status === 'verified')
                        Tu CURP ha sido verificado correctamente.
                    @elseif($user->curp_verification_status === 'pending')
                        Tu CURP esta pendiente de verificacion.
                    @else
                        Tu CURP aun no ha sido verificado.
                    @endif
                </p>
            </div>
        </div>

        <div class="verification-status {{ $user->face_verification_status === 'verified' ? 'verified' : ($user->face_verification_status === 'pending' ? 'pending' : 'unverified') }}">
            <i class="fas {{ $user->face_verification_status === 'verified' ? 'fa-check-circle' : ($user->face_verification_status === 'pending' ? 'fa-clock' : 'fa-times-circle') }}"></i>
            <div class="verification-info">
                <h4>Verificacion Facial</h4>
                <p>
                    @if($user->face_verification_status === 'verified')
                        Tu identidad ha sido verificada mediante reconocimiento facial.
                    @elseif($user->face_verification_status === 'pending')
                        Tu verificacion facial esta pendiente.
                    @else
                        Aun no has completado la verificacion facial.
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Personal Information Form -->
    <div class="profile-section">
        <h3 class="section-title"><i class="fas fa-user"></i> Informacion Personal</h3>

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label for="nombres">Nombre(s)</label>
                    <input type="text" id="nombres" name="nombres" value="{{ old('nombres', $user->nombres) }}" placeholder="Tu nombre">
                </div>

                <div class="form-group">
                    <label for="apellido_paterno">Apellido Paterno</label>
                    <input type="text" id="apellido_paterno" name="apellido_paterno" value="{{ old('apellido_paterno', $user->apellido_paterno) }}" placeholder="Apellido paterno">
                </div>

                <div class="form-group">
                    <label for="apellido_materno">Apellido Materno</label>
                    <input type="text" id="apellido_materno" name="apellido_materno" value="{{ old('apellido_materno', $user->apellido_materno) }}" placeholder="Apellido materno">
                </div>

                <div class="form-group">
                    <label for="curp">CURP</label>
                    <input type="text" id="curp" name="curp" value="{{ old('curp', $user->curp) }}" placeholder="18 caracteres" maxlength="18" {{ $user->curp_verification_status === 'verified' ? 'disabled' : '' }}>
                    @if($user->curp_verification_status === 'verified')
                        <span class="input-hint"><i class="fas fa-lock"></i> CURP verificado, no puede ser modificado</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="email">Correo Electronico</label>
                    <input type="email" id="email" value="{{ $user->email }}" disabled>
                    <span class="input-hint"><i class="fas fa-lock"></i> El correo no puede ser modificado</span>
                </div>

                <div class="form-group">
                    <label for="telefono_movil">Telefono Movil</label>
                    <input type="tel" id="telefono_movil" name="telefono_movil" value="{{ old('telefono_movil', $user->telefono_movil) }}" placeholder="10 digitos">
                </div>

                <div class="form-group">
                    <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $user->fecha_nacimiento ? $user->fecha_nacimiento->format('Y-m-d') : '') }}">
                </div>

                <div class="form-group">
                    <label for="sexo">Sexo</label>
                    <select id="sexo" name="sexo">
                        <option value="">Seleccionar...</option>
                        <option value="M" {{ old('sexo', $user->sexo) === 'M' ? 'selected' : '' }}>Masculino</option>
                        <option value="F" {{ old('sexo', $user->sexo) === 'F' ? 'selected' : '' }}>Femenino</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="nacionalidad">Nacionalidad</label>
                    <input type="text" id="nacionalidad" name="nacionalidad" value="{{ old('nacionalidad', $user->nacionalidad) }}" placeholder="Ej: Mexicana">
                </div>

                <div class="form-group">
                    <label for="pais_nacimiento">Pais de Nacimiento</label>
                    <input type="text" id="pais_nacimiento" name="pais_nacimiento" value="{{ old('pais_nacimiento', $user->pais_nacimiento) }}" placeholder="Ej: Mexico">
                </div>
            </div>

            <h3 class="section-title" style="margin-top: 30px;"><i class="fas fa-map-marker-alt"></i> Direccion</h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="calle">Calle</label>
                    <input type="text" id="calle" name="calle" value="{{ old('calle', $user->calle) }}" placeholder="Nombre de la calle">
                </div>

                <div class="form-group">
                    <label for="numero_exterior">Numero Exterior</label>
                    <input type="text" id="numero_exterior" name="numero_exterior" value="{{ old('numero_exterior', $user->numero_exterior) }}" placeholder="Num. Ext.">
                </div>

                <div class="form-group">
                    <label for="numero_interior">Numero Interior</label>
                    <input type="text" id="numero_interior" name="numero_interior" value="{{ old('numero_interior', $user->numero_interior) }}" placeholder="Num. Int. (opcional)">
                </div>

                <div class="form-group">
                    <label for="codigo_postal">Codigo Postal</label>
                    <input type="text" id="codigo_postal" name="codigo_postal" value="{{ old('codigo_postal', $user->codigo_postal) }}" placeholder="5 digitos" maxlength="5">
                </div>

                <div class="form-group">
                    <label for="localidad">Colonia / Localidad</label>
                    <input type="text" id="localidad" name="localidad" value="{{ old('localidad', $user->localidad) }}" placeholder="Colonia">
                </div>

                <div class="form-group">
                    <label for="municipio">Municipio / Alcaldia</label>
                    <input type="text" id="municipio" name="municipio" value="{{ old('municipio', $user->municipio) }}" placeholder="Municipio">
                </div>

                <div class="form-group">
                    <label for="estado">Estado</label>
                    <input type="text" id="estado" name="estado" value="{{ old('estado', $user->estado) }}" placeholder="Estado">
                </div>
            </div>

            <div class="btn-container">
                <button type="button" class="btn btn-secondary" onclick="window.location.reload()">
                    <i class="fas fa-undo"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</section>
@endsection
