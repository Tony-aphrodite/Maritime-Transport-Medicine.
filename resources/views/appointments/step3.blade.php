@extends('layouts.dashboard')

@section('title', 'Agendar Cita - Declaracion Medica')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/appointments.css') }}">
@endpush

@section('content')
<section class="booking-container">
    <!-- Back Navigation -->
    <div class="back-nav">
        <a href="{{ route('appointments.step2') }}" class="btn-back-link">
            <i class="fas fa-arrow-left"></i> Volver a Archivos
        </a>
    </div>

    <!-- Stepper -->
    <div class="stepper">
        <div class="step completed"><span><i class="fas fa-check"></i></span><p>Fecha</p></div>
        <div class="step completed"><span><i class="fas fa-check"></i></span><p>Archivos</p></div>
        <div class="step active"><span>3</span><p>Declaracion</p></div>
        <div class="step"><span>4</span><p>Confirmar</p></div>
        <div class="step"><span>5</span><p>Pago</p></div>
    </div>

    <div class="card-white medical-declaration">
        <div class="declaration-header">
            <h3>Declaracion de Salud del Candidato</h3>
            <p>Responda con honestidad. Esta informacion es confidencial y necesaria para su Dictamen de Aptitud.<br><br>
                El proposito de esta revision medica es guiar al medico para que le aconseje sobre el manejo de afecciones en un ambiente aislado donde el apoyo medico puede ser limitado, para evitar que las circunstancias empeoren o pongan a usted o a otros en riesgo.
                <br><br>
                Todas las condiciones se evaluaran de acuerdo con las pautas de medicina preventiva del transporte maritimo y la Regla I/9, del Convenio STCW 1978, en su forma enmendada.
            </p>
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

        <form action="{{ route('appointments.step3.process') }}" method="POST" id="step3Form" class="health-form">
            @csrf

            <!-- Work Information Section -->
            <div class="form-section-highlight">
                <h4><i class="fas fa-ship"></i> Informacion Laboral</h4>

                <div class="question-group">
                    <p class="question-text">¿Es tu primer examen medico en medicina preventiva del transporte?</p>
                    <div class="radio-options">
                        <label class="radio-item">
                            <input type="radio" name="exam_type" value="new"
                                   {{ old('exam_type', session('appointment.medical_declaration.exam_type')) == 'new' ? 'checked' : '' }} required>
                            <span>Si, es el primero</span>
                        </label>
                        <label class="radio-item">
                            <input type="radio" name="exam_type" value="renewal"
                                   {{ old('exam_type', session('appointment.medical_declaration.exam_type')) == 'renewal' ? 'checked' : '' }}>
                            <span>No, es renovacion</span>
                        </label>
                    </div>
                </div>

                <div class="form-group inline-group">
                    <label>¿Cuantos anos has estado trabajando en la mar?</label>
                    <div class="input-unit">
                        <input type="number" name="years_at_sea" min="0" max="60" placeholder="0"
                               value="{{ old('years_at_sea', session('appointment.medical_declaration.years_at_sea', 0)) }}" required>
                        <span>anos</span>
                    </div>
                </div>

                <div class="question-group">
                    <p class="question-text">¿A que tipo de riesgos laborales estas expuesto en tu area de trabajo?</p>
                    @php
                        $risks = session('appointment.medical_declaration.workplace_risks', []);
                        if (!is_array($risks)) $risks = [];
                    @endphp
                    <div class="check-grid">
                        <label class="check-item">
                            <input type="checkbox" name="workplace_risks[]" value="none"
                                   {{ in_array('none', old('workplace_risks', $risks)) ? 'checked' : '' }}>
                            <span>Ninguno</span>
                        </label>
                        <label class="check-item">
                            <input type="checkbox" name="workplace_risks[]" value="noise"
                                   {{ in_array('noise', old('workplace_risks', $risks)) ? 'checked' : '' }}>
                            <span>Ruido</span>
                        </label>
                        <label class="check-item">
                            <input type="checkbox" name="workplace_risks[]" value="dust"
                                   {{ in_array('dust', old('workplace_risks', $risks)) ? 'checked' : '' }}>
                            <span>Polvo</span>
                        </label>
                        <label class="check-item">
                            <input type="checkbox" name="workplace_risks[]" value="radiation"
                                   {{ in_array('radiation', old('workplace_risks', $risks)) ? 'checked' : '' }}>
                            <span>Radiacion</span>
                        </label>
                        <label class="check-item">
                            <input type="checkbox" name="workplace_risks[]" value="other"
                                   {{ in_array('other', old('workplace_risks', $risks)) ? 'checked' : '' }}>
                            <span>Otro</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Health Conditions Section -->
            <div class="form-section">
                <p class="question-text">¿Sufre o ha sufrido alguna de las siguientes condiciones?</p>
                @php
                    $conditions = session('appointment.medical_declaration.health_conditions', []);
                    if (!is_array($conditions)) $conditions = [];
                @endphp
                <div class="check-grid">
                    <label class="check-item">
                        <input type="checkbox" name="health_conditions[]" value="high_blood_pressure"
                               {{ in_array('high_blood_pressure', old('health_conditions', $conditions)) ? 'checked' : '' }}>
                        <span>Presion arterial alta</span>
                    </label>
                    <label class="check-item">
                        <input type="checkbox" name="health_conditions[]" value="diabetes"
                               {{ in_array('diabetes', old('health_conditions', $conditions)) ? 'checked' : '' }}>
                        <span>Diabetes</span>
                    </label>
                    <label class="check-item">
                        <input type="checkbox" name="health_conditions[]" value="hearing_vision"
                               {{ in_array('hearing_vision', old('health_conditions', $conditions)) ? 'checked' : '' }}>
                        <span>Problemas de audicion / Vision</span>
                    </label>
                    <label class="check-item">
                        <input type="checkbox" name="health_conditions[]" value="recent_surgeries"
                               {{ in_array('recent_surgeries', old('health_conditions', $conditions)) ? 'checked' : '' }}>
                        <span>Cirugias recientes</span>
                    </label>
                </div>
            </div>

            <!-- Medications and Observations -->
            <div class="form-group mt-30">
                <label>Medicamentos actuales y observaciones:</label>
                <textarea name="additional_notes" placeholder="Especifique medicamento, dosis o cualquier informacion que considere relevante...">{{ old('additional_notes', session('appointment.medical_declaration.additional_notes')) }}</textarea>
            </div>

            <!-- Declaration Confirmation -->
            <div class="declaration-confirm">
                <label class="confirm-label">
                    <input type="checkbox" name="declaration_truthful" value="1"
                           {{ old('declaration_truthful', session('appointment.medical_declaration.declaration_truthful')) ? 'checked' : '' }} required>
                    <span>Declaro bajo protesta de decir verdad que la informacion proporcionada es veridica y actual.</span>
                </label>
            </div>

            <div class="action-footer">
                <a href="{{ route('appointments.step2') }}" class="btn-back-gold">
                    <i class="fas fa-arrow-left"></i> Atras
                </a>
                <button type="submit" class="btn-primary-gold">
                    Continuar a Confirmacion <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle "Ninguno" checkbox - uncheck others when selected
    const noneCheckbox = document.querySelector('input[value="none"]');
    const riskCheckboxes = document.querySelectorAll('input[name="workplace_risks[]"]:not([value="none"])');

    if (noneCheckbox) {
        noneCheckbox.addEventListener('change', function() {
            if (this.checked) {
                riskCheckboxes.forEach(cb => cb.checked = false);
            }
        });

        riskCheckboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                if (this.checked) {
                    noneCheckbox.checked = false;
                }
            });
        });
    }
});
</script>
@endpush
