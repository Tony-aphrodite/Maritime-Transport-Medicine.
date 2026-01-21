@extends('layouts.dashboard')

@section('title', 'Agendar Cita - Declaracion Medica')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/appointments.css') }}">
@endpush

@section('content')
<section class="appointment-dashboard">
    <!-- Stepper -->
    <div class="stepper">
        <div class="step completed">
            <div class="step-number"></div>
            <span class="step-label">Fecha y Hora</span>
        </div>
        <div class="step completed">
            <div class="step-number"></div>
            <span class="step-label">Archivos</span>
        </div>
        <div class="step active">
            <div class="step-number">3</div>
            <span class="step-label">Declaracion</span>
        </div>
        <div class="step">
            <div class="step-number">4</div>
            <span class="step-label">Confirmacion</span>
        </div>
        <div class="step">
            <div class="step-number">5</div>
            <span class="step-label">Pago</span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="appointment-container">
        <div class="appointment-header">
            <h2><i class="fas fa-file-medical"></i> Declaracion Medica</h2>
            <p>Complete el siguiente formulario con informacion sobre su historial medico y condiciones de trabajo.</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <ul style="margin: 0; padding-left: 1.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('appointments.step3.process') }}" method="POST" id="step3Form">
            @csrf

            <!-- Exam Type Section -->
            <div class="form-section">
                <h3><i class="fas fa-stethoscope"></i> Tipo de Examen</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label>Tipo de Dictamen <span class="required">*</span></label>
                        <div class="radio-group" style="display: flex; gap: 2rem; margin-top: 0.5rem;">
                            <label class="radio-item" style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="radio" name="exam_type" value="new" {{ old('exam_type', session('appointment.medical_declaration.exam_type')) == 'new' ? 'checked' : '' }} required>
                                <span>Examen Nuevo</span>
                            </label>
                            <label class="radio-item" style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="radio" name="exam_type" value="renewal" {{ old('exam_type', session('appointment.medical_declaration.exam_type')) == 'renewal' ? 'checked' : '' }}>
                                <span>Renovacion</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Work Information Section -->
            <div class="form-section">
                <h3><i class="fas fa-ship"></i> Informacion Laboral</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="years_at_sea">Anos en el Mar <span class="required">*</span></label>
                        <input type="number" name="years_at_sea" id="years_at_sea" min="0" max="50"
                               value="{{ old('years_at_sea', session('appointment.medical_declaration.years_at_sea', 0)) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="current_position">Puesto Actual <span class="required">*</span></label>
                        <input type="text" name="current_position" id="current_position"
                               value="{{ old('current_position', session('appointment.medical_declaration.current_position')) }}"
                               placeholder="Ej: Capitan, Oficial, Marinero" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="vessel_type">Tipo de Embarcacion <span class="required">*</span></label>
                    <select name="vessel_type" id="vessel_type" required>
                        <option value="">Seleccione...</option>
                        <option value="cargo" {{ old('vessel_type', session('appointment.medical_declaration.vessel_type')) == 'cargo' ? 'selected' : '' }}>Buque de Carga</option>
                        <option value="tanker" {{ old('vessel_type', session('appointment.medical_declaration.vessel_type')) == 'tanker' ? 'selected' : '' }}>Buque Tanque</option>
                        <option value="passenger" {{ old('vessel_type', session('appointment.medical_declaration.vessel_type')) == 'passenger' ? 'selected' : '' }}>Buque de Pasajeros</option>
                        <option value="fishing" {{ old('vessel_type', session('appointment.medical_declaration.vessel_type')) == 'fishing' ? 'selected' : '' }}>Buque Pesquero</option>
                        <option value="offshore" {{ old('vessel_type', session('appointment.medical_declaration.vessel_type')) == 'offshore' ? 'selected' : '' }}>Plataforma Offshore</option>
                        <option value="tugboat" {{ old('vessel_type', session('appointment.medical_declaration.vessel_type')) == 'tugboat' ? 'selected' : '' }}>Remolcador</option>
                        <option value="other" {{ old('vessel_type', session('appointment.medical_declaration.vessel_type')) == 'other' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>
            </div>

            <!-- Health History Section -->
            <div class="form-section">
                <h3><i class="fas fa-heartbeat"></i> Historial de Salud</h3>

                <!-- Chronic Conditions -->
                <div class="form-group">
                    <label>¿Padece alguna enfermedad cronica? <span class="required">*</span></label>
                    <div class="radio-group" style="display: flex; gap: 2rem; margin-top: 0.5rem;">
                        <label class="radio-item" style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="radio" name="has_chronic_conditions" value="1"
                                   {{ old('has_chronic_conditions', session('appointment.medical_declaration.has_chronic_conditions')) == '1' ? 'checked' : '' }}
                                   onchange="toggleDetail('chronic')" required>
                            <span>Si</span>
                        </label>
                        <label class="radio-item" style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="radio" name="has_chronic_conditions" value="0"
                                   {{ old('has_chronic_conditions', session('appointment.medical_declaration.has_chronic_conditions')) === '0' ? 'checked' : '' }}
                                   onchange="toggleDetail('chronic')">
                            <span>No</span>
                        </label>
                    </div>
                    <div id="chronicDetail" style="display: none; margin-top: 0.5rem;">
                        <textarea name="chronic_conditions_detail" rows="2"
                                  placeholder="Describa las condiciones cronicas...">{{ old('chronic_conditions_detail', session('appointment.medical_declaration.chronic_conditions_detail')) }}</textarea>
                    </div>
                </div>

                <!-- Medications -->
                <div class="form-group">
                    <label>¿Toma medicamentos actualmente? <span class="required">*</span></label>
                    <div class="radio-group" style="display: flex; gap: 2rem; margin-top: 0.5rem;">
                        <label class="radio-item" style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="radio" name="takes_medications" value="1"
                                   {{ old('takes_medications', session('appointment.medical_declaration.takes_medications')) == '1' ? 'checked' : '' }}
                                   onchange="toggleDetail('medications')" required>
                            <span>Si</span>
                        </label>
                        <label class="radio-item" style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="radio" name="takes_medications" value="0"
                                   {{ old('takes_medications', session('appointment.medical_declaration.takes_medications')) === '0' ? 'checked' : '' }}
                                   onchange="toggleDetail('medications')">
                            <span>No</span>
                        </label>
                    </div>
                    <div id="medicationsDetail" style="display: none; margin-top: 0.5rem;">
                        <textarea name="medications_detail" rows="2"
                                  placeholder="Liste los medicamentos que toma...">{{ old('medications_detail', session('appointment.medical_declaration.medications_detail')) }}</textarea>
                    </div>
                </div>

                <!-- Allergies -->
                <div class="form-group">
                    <label>¿Tiene alguna alergia conocida? <span class="required">*</span></label>
                    <div class="radio-group" style="display: flex; gap: 2rem; margin-top: 0.5rem;">
                        <label class="radio-item" style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="radio" name="has_allergies" value="1"
                                   {{ old('has_allergies', session('appointment.medical_declaration.has_allergies')) == '1' ? 'checked' : '' }}
                                   onchange="toggleDetail('allergies')" required>
                            <span>Si</span>
                        </label>
                        <label class="radio-item" style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="radio" name="has_allergies" value="0"
                                   {{ old('has_allergies', session('appointment.medical_declaration.has_allergies')) === '0' ? 'checked' : '' }}
                                   onchange="toggleDetail('allergies')">
                            <span>No</span>
                        </label>
                    </div>
                    <div id="allergiesDetail" style="display: none; margin-top: 0.5rem;">
                        <textarea name="allergies_detail" rows="2"
                                  placeholder="Describa sus alergias...">{{ old('allergies_detail', session('appointment.medical_declaration.allergies_detail')) }}</textarea>
                    </div>
                </div>

                <!-- Surgeries -->
                <div class="form-group">
                    <label>¿Ha tenido cirugias previas? <span class="required">*</span></label>
                    <div class="radio-group" style="display: flex; gap: 2rem; margin-top: 0.5rem;">
                        <label class="radio-item" style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="radio" name="has_surgeries" value="1"
                                   {{ old('has_surgeries', session('appointment.medical_declaration.has_surgeries')) == '1' ? 'checked' : '' }}
                                   onchange="toggleDetail('surgeries')" required>
                            <span>Si</span>
                        </label>
                        <label class="radio-item" style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="radio" name="has_surgeries" value="0"
                                   {{ old('has_surgeries', session('appointment.medical_declaration.has_surgeries')) === '0' ? 'checked' : '' }}
                                   onchange="toggleDetail('surgeries')">
                            <span>No</span>
                        </label>
                    </div>
                    <div id="surgeriesDetail" style="display: none; margin-top: 0.5rem;">
                        <textarea name="surgeries_detail" rows="2"
                                  placeholder="Describa las cirugias previas...">{{ old('surgeries_detail', session('appointment.medical_declaration.surgeries_detail')) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Workplace Risks Section -->
            <div class="form-section">
                <h3><i class="fas fa-exclamation-triangle"></i> Riesgos Laborales</h3>
                <p style="color: #666; font-size: 0.9rem; margin-bottom: 1rem;">Seleccione los riesgos a los que esta expuesto en su trabajo:</p>
                <div class="checkbox-group" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.75rem;">
                    @php
                        $risks = session('appointment.medical_declaration.workplace_risks', []);
                        if (!is_array($risks)) $risks = [];
                    @endphp
                    <label class="checkbox-item" style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.75rem; background: #f8f9fa; border-radius: 8px;">
                        <input type="checkbox" name="workplace_risks[]" value="noise"
                               {{ in_array('noise', old('workplace_risks', $risks)) ? 'checked' : '' }}>
                        <i class="fas fa-volume-up" style="color: #d4af37;"></i>
                        <span>Ruido</span>
                    </label>
                    <label class="checkbox-item" style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.75rem; background: #f8f9fa; border-radius: 8px;">
                        <input type="checkbox" name="workplace_risks[]" value="dust"
                               {{ in_array('dust', old('workplace_risks', $risks)) ? 'checked' : '' }}>
                        <i class="fas fa-wind" style="color: #d4af37;"></i>
                        <span>Polvo</span>
                    </label>
                    <label class="checkbox-item" style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.75rem; background: #f8f9fa; border-radius: 8px;">
                        <input type="checkbox" name="workplace_risks[]" value="chemicals"
                               {{ in_array('chemicals', old('workplace_risks', $risks)) ? 'checked' : '' }}>
                        <i class="fas fa-flask" style="color: #d4af37;"></i>
                        <span>Quimicos</span>
                    </label>
                    <label class="checkbox-item" style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.75rem; background: #f8f9fa; border-radius: 8px;">
                        <input type="checkbox" name="workplace_risks[]" value="vibration"
                               {{ in_array('vibration', old('workplace_risks', $risks)) ? 'checked' : '' }}>
                        <i class="fas fa-wave-square" style="color: #d4af37;"></i>
                        <span>Vibracion</span>
                    </label>
                    <label class="checkbox-item" style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.75rem; background: #f8f9fa; border-radius: 8px;">
                        <input type="checkbox" name="workplace_risks[]" value="heights"
                               {{ in_array('heights', old('workplace_risks', $risks)) ? 'checked' : '' }}>
                        <i class="fas fa-arrow-up" style="color: #d4af37;"></i>
                        <span>Alturas</span>
                    </label>
                    <label class="checkbox-item" style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.75rem; background: #f8f9fa; border-radius: 8px;">
                        <input type="checkbox" name="workplace_risks[]" value="confined_spaces"
                               {{ in_array('confined_spaces', old('workplace_risks', $risks)) ? 'checked' : '' }}>
                        <i class="fas fa-compress-alt" style="color: #d4af37;"></i>
                        <span>Espacios Confinados</span>
                    </label>
                </div>
            </div>

            <!-- Additional Notes -->
            <div class="form-section">
                <h3><i class="fas fa-comment-medical"></i> Notas Adicionales</h3>
                <div class="form-group">
                    <label for="additional_notes">¿Hay algo mas que debamos saber?</label>
                    <textarea name="additional_notes" id="additional_notes" rows="3"
                              placeholder="Agregue cualquier informacion adicional relevante para su examen medico...">{{ old('additional_notes', session('appointment.medical_declaration.additional_notes')) }}</textarea>
                </div>
            </div>

            <!-- Navigation -->
            <div class="step-navigation">
                <a href="{{ route('appointments.step2') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Atras
                </a>
                <button type="submit" class="btn-next">
                    Siguiente <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
function toggleDetail(type) {
    const detailMap = {
        'chronic': 'chronicDetail',
        'medications': 'medicationsDetail',
        'allergies': 'allergiesDetail',
        'surgeries': 'surgeriesDetail'
    };

    const inputMap = {
        'chronic': 'has_chronic_conditions',
        'medications': 'takes_medications',
        'allergies': 'has_allergies',
        'surgeries': 'has_surgeries'
    };

    const detailEl = document.getElementById(detailMap[type]);
    const yesSelected = document.querySelector(`input[name="${inputMap[type]}"][value="1"]`).checked;

    detailEl.style.display = yesSelected ? 'block' : 'none';
}

// Initialize visibility on page load
document.addEventListener('DOMContentLoaded', function() {
    ['chronic', 'medications', 'allergies', 'surgeries'].forEach(type => {
        toggleDetail(type);
    });
});
</script>
@endpush
