@extends('layouts.dashboard')

@section('title', 'Agendar Cita - Subir Estudios Medicos')

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
        <div class="step active">
            <div class="step-number">2</div>
            <span class="step-label">Estudios Medicos</span>
        </div>
        <div class="step">
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
            <h2><i class="fas fa-file-medical"></i> Subir Estudios Medicos</h2>
            <p>Suba los resultados de sus estudios medicos requeridos para la evaluacion. Los archivos se almacenan de forma segura.</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                {{ session('error') }}
            </div>
        @endif

        <!-- Document Requirements -->
        <div class="form-section">
            <h3><i class="fas fa-list-check"></i> Estudios Requeridos</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 8px;">
                    <i class="fas fa-vial" style="color: #d4af37; font-size: 1.2rem;"></i>
                    <div>
                        <strong>Biometria Hematica</strong>
                        <p style="font-size: 0.85rem; color: #666; margin: 0;">Analisis de sangre completo</p>
                    </div>
                    <span class="document-status pending" id="status-blood_test">Pendiente</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 8px;">
                    <i class="fas fa-flask" style="color: #d4af37; font-size: 1.2rem;"></i>
                    <div>
                        <strong>Quimica Sanguinea</strong>
                        <p style="font-size: 0.85rem; color: #666; margin: 0;">Panel de quimica sanguinea</p>
                    </div>
                    <span class="document-status pending" id="status-chemistry">Pendiente</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 8px;">
                    <i class="fas fa-tint" style="color: #d4af37; font-size: 1.2rem;"></i>
                    <div>
                        <strong>Examen General de Orina</strong>
                        <p style="font-size: 0.85rem; color: #666; margin: 0;">Analisis de orina</p>
                    </div>
                    <span class="document-status pending" id="status-urine_test">Pendiente</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 8px;">
                    <i class="fas fa-x-ray" style="color: #d4af37; font-size: 1.2rem;"></i>
                    <div>
                        <strong>Radiografia de Torax</strong>
                        <p style="font-size: 0.85rem; color: #666; margin: 0;">Rayos X de torax</p>
                    </div>
                    <span class="document-status pending" id="status-chest_xray">Pendiente</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 8px;">
                    <i class="fas fa-heartbeat" style="color: #d4af37; font-size: 1.2rem;"></i>
                    <div>
                        <strong>Electrocardiograma</strong>
                        <p style="font-size: 0.85rem; color: #666; margin: 0;">ECG / EKG</p>
                    </div>
                    <span class="document-status pending" id="status-ecg">Pendiente</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 8px;">
                    <i class="fas fa-eye" style="color: #d4af37; font-size: 1.2rem;"></i>
                    <div>
                        <strong>Examen de Vista</strong>
                        <p style="font-size: 0.85rem; color: #666; margin: 0;">Agudeza visual y colores</p>
                    </div>
                    <span class="document-status pending" id="status-vision_test">Pendiente</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 8px;">
                    <i class="fas fa-ear-listen" style="color: #d4af37; font-size: 1.2rem;"></i>
                    <div>
                        <strong>Audiometria</strong>
                        <p style="font-size: 0.85rem; color: #666; margin: 0;">Examen de audicion</p>
                    </div>
                    <span class="document-status pending" id="status-audiometry">Pendiente</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; border-radius: 8px;">
                    <i class="fas fa-file-medical-alt" style="color: #d4af37; font-size: 1.2rem;"></i>
                    <div>
                        <strong>Otros Estudios</strong>
                        <p style="font-size: 0.85rem; color: #666; margin: 0;">Estudios adicionales (opcional)</p>
                    </div>
                    <span class="document-status pending" id="status-other_medical">Opcional</span>
                </div>
            </div>
        </div>

        <!-- Upload Area -->
        <div class="upload-container">
            <div class="upload-area" id="dropZone">
                <i class="fas fa-cloud-upload-alt"></i>
                <h4>Arrastre y suelte sus archivos aqui</h4>
                <p>o haga clic para seleccionar archivos</p>
                <p style="font-size: 0.8rem; color: #999;">Formatos aceptados: PDF, JPG, PNG (Max. 10MB)</p>
                <input type="file" id="fileInput" accept=".pdf,.jpg,.jpeg,.png" multiple style="display: none;">
                <button type="button" class="btn-browse" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-folder-open"></i> Seleccionar Archivos
                </button>
            </div>

            <!-- Document Type Selection Modal -->
            <div id="documentTypeModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
                <div style="background: white; padding: 2rem; border-radius: 12px; max-width: 400px; width: 90%;">
                    <h3 style="margin-bottom: 1rem;"><i class="fas fa-file-medical" style="color: #d4af37;"></i> Tipo de Estudio</h3>
                    <p style="margin-bottom: 1rem; color: #666;">Seleccione el tipo de estudio que esta subiendo:</p>
                    <p id="uploadingFileName" style="font-weight: 600; margin-bottom: 1rem;"></p>
                    <select id="documentTypeSelect" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; margin-bottom: 1.5rem;">
                        <option value="blood_test">Biometria Hematica</option>
                        <option value="chemistry">Quimica Sanguinea</option>
                        <option value="urine_test">Examen General de Orina</option>
                        <option value="chest_xray">Radiografia de Torax</option>
                        <option value="ecg">Electrocardiograma (ECG)</option>
                        <option value="vision_test">Examen de Vista</option>
                        <option value="audiometry">Audiometria</option>
                        <option value="other_medical">Otros Estudios</option>
                    </select>
                    <div style="display: flex; gap: 1rem;">
                        <button type="button" id="cancelUpload" style="flex: 1; padding: 0.75rem; border: 2px solid #e0e0e0; background: white; border-radius: 8px; cursor: pointer;">Cancelar</button>
                        <button type="button" id="confirmUpload" style="flex: 1; padding: 0.75rem; background: linear-gradient(135deg, #d4af37 0%, #c5a028 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Subir</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Uploaded Documents Table -->
        <table class="documents-table" id="documentsTable">
            <thead>
                <tr>
                    <th>Estudio</th>
                    <th>Tipo</th>
                    <th>Tamano</th>
                    <th>Estado</th>
                    <th>Accion</th>
                </tr>
            </thead>
            <tbody id="documentsTableBody">
                @forelse($documents as $document)
                <tr data-id="{{ $document->id }}" data-type="{{ $document->document_type }}">
                    <td>
                        @if(str_contains($document->mime_type ?? '', 'pdf'))
                            <i class="fas fa-file-pdf" style="color: #dc3545; margin-right: 0.5rem;"></i>
                        @else
                            <i class="fas fa-file-image" style="color: #28a745; margin-right: 0.5rem;"></i>
                        @endif
                        {{ $document->original_name }}
                    </td>
                    <td>{{ $document->document_type_label }}</td>
                    <td>{{ $document->formatted_size }}</td>
                    <td><span class="document-status uploaded"><i class="fas fa-check-circle"></i> Subido</span></td>
                    <td>
                        <button type="button" class="btn-delete" onclick="deleteDocument({{ $document->id }})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr id="noDocumentsRow">
                    <td colspan="5" style="text-align: center; color: #666; padding: 2rem;">
                        <i class="fas fa-folder-open" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; color: #ccc;"></i>
                        No hay estudios subidos todavia.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Info Note -->
        <div style="background: #e3f2fd; border-left: 4px solid #2196f3; padding: 1rem; border-radius: 8px; margin: 1.5rem 0;">
            <p style="margin: 0; color: #1565c0;">
                <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i>
                <strong>Nota:</strong> Es necesario subir al menos los estudios basicos (Biometria, Quimica, Orina, Radiografia, ECG, Vista y Audiometria) para continuar con su cita.
            </p>
        </div>

        <!-- Navigation -->
        <form action="{{ route('appointments.step2.process') }}" method="POST" id="step2Form">
            @csrf
            <div class="step-navigation">
                <a href="{{ route('appointments.step1') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Atras
                </a>
                <button type="submit" class="btn-next" id="btnNext">
                    Siguiente <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const modal = document.getElementById('documentTypeModal');
    const documentTypeSelect = document.getElementById('documentTypeSelect');
    const uploadingFileName = document.getElementById('uploadingFileName');
    const documentsTableBody = document.getElementById('documentsTableBody');
    const noDocumentsRow = document.getElementById('noDocumentsRow');

    let pendingFile = null;

    // Drag and drop handlers
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('drag-over');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('drag-over');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFile(files[0]);
        }
    });

    // File input change handler
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleFile(e.target.files[0]);
        }
    });

    // Click to upload
    dropZone.addEventListener('click', (e) => {
        if (e.target.classList.contains('btn-browse') || e.target.closest('.btn-browse')) {
            return; // Let the button's onclick handle it
        }
        fileInput.click();
    });

    function handleFile(file) {
        // Validate file type
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            alert('Tipo de archivo no permitido. Solo se aceptan PDF, JPG y PNG.');
            return;
        }

        // Validate file size (10MB max)
        if (file.size > 10 * 1024 * 1024) {
            alert('El archivo es demasiado grande. El tamano maximo es 10MB.');
            return;
        }

        pendingFile = file;
        uploadingFileName.textContent = file.name;
        modal.style.display = 'flex';
    }

    // Modal handlers
    document.getElementById('cancelUpload').addEventListener('click', () => {
        modal.style.display = 'none';
        pendingFile = null;
        fileInput.value = '';
    });

    document.getElementById('confirmUpload').addEventListener('click', () => {
        if (!pendingFile) return;

        const formData = new FormData();
        formData.append('file', pendingFile);
        formData.append('document_type', documentTypeSelect.value);
        formData.append('_token', '{{ csrf_token() }}');

        modal.style.display = 'none';

        // Show uploading state
        const uploadingRow = document.createElement('tr');
        uploadingRow.innerHTML = `
            <td><i class="fas fa-spinner fa-spin"></i> ${pendingFile.name}</td>
            <td>-</td>
            <td>-</td>
            <td><span class="document-status pending">Subiendo...</span></td>
            <td>-</td>
        `;
        if (noDocumentsRow) noDocumentsRow.remove();
        documentsTableBody.appendChild(uploadingRow);

        fetch('{{ route("appointments.upload") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            uploadingRow.remove();

            if (data.success) {
                addDocumentRow(data.document);
                updateDocumentStatus(data.document.document_type, true);
            } else {
                alert(data.message || 'Error al subir el archivo.');
            }
        })
        .catch(error => {
            uploadingRow.remove();
            alert('Error al subir el archivo. Por favor, intente de nuevo.');
            console.error('Upload error:', error);
        });

        pendingFile = null;
        fileInput.value = '';
    });

    function addDocumentRow(doc) {
        const typeLabels = {
            'blood_test': 'Biometria Hematica',
            'chemistry': 'Quimica Sanguinea',
            'urine_test': 'Examen General de Orina',
            'chest_xray': 'Radiografia de Torax',
            'ecg': 'Electrocardiograma',
            'vision_test': 'Examen de Vista',
            'audiometry': 'Audiometria',
            'other_medical': 'Otros Estudios'
        };

        const fileIcon = doc.mime_type && doc.mime_type.includes('pdf') ? 'fa-file-pdf' : 'fa-file-image';
        const fileIconColor = doc.mime_type && doc.mime_type.includes('pdf') ? '#dc3545' : '#28a745';

        const row = document.createElement('tr');
        row.setAttribute('data-id', doc.id);
        row.setAttribute('data-type', doc.document_type);
        row.innerHTML = `
            <td><i class="fas ${fileIcon}" style="color: ${fileIconColor}; margin-right: 0.5rem;"></i> ${doc.original_name}</td>
            <td>${typeLabels[doc.document_type] || doc.document_type}</td>
            <td>${formatFileSize(doc.file_size)}</td>
            <td><span class="document-status uploaded"><i class="fas fa-check-circle"></i> Subido</span></td>
            <td><button type="button" class="btn-delete" onclick="deleteDocument(${doc.id})"><i class="fas fa-trash-alt"></i></button></td>
        `;
        documentsTableBody.appendChild(row);
    }

    function formatFileSize(bytes) {
        if (bytes >= 1048576) {
            return (bytes / 1048576).toFixed(2) + ' MB';
        } else if (bytes >= 1024) {
            return (bytes / 1024).toFixed(2) + ' KB';
        }
        return bytes + ' bytes';
    }

    window.deleteDocument = function(id) {
        if (!confirm('¿Esta seguro de eliminar este estudio?')) return;

        const row = document.querySelector(`tr[data-id="${id}"]`);
        const docType = row.getAttribute('data-type');

        fetch(`{{ url('appointments/document') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                row.remove();
                updateDocumentStatus(docType, false);

                // Check if table is empty
                if (documentsTableBody.children.length === 0) {
                    documentsTableBody.innerHTML = `
                        <tr id="noDocumentsRow">
                            <td colspan="5" style="text-align: center; color: #666; padding: 2rem;">
                                <i class="fas fa-folder-open" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; color: #ccc;"></i>
                                No hay estudios subidos todavia.
                            </td>
                        </tr>
                    `;
                }
            } else {
                alert(data.message || 'Error al eliminar el estudio.');
            }
        })
        .catch(error => {
            alert('Error al eliminar el estudio.');
            console.error('Delete error:', error);
        });
    };

    function updateDocumentStatus(docType, uploaded) {
        const statusEl = document.getElementById(`status-${docType}`);
        if (statusEl) {
            if (uploaded) {
                statusEl.className = 'document-status uploaded';
                statusEl.innerHTML = '<i class="fas fa-check-circle"></i> Subido';
            } else {
                // Check if there's still another document of this type
                const stillExists = document.querySelector(`tr[data-type="${docType}"]`);
                if (!stillExists) {
                    statusEl.className = 'document-status pending';
                    statusEl.textContent = docType === 'other_medical' ? 'Opcional' : 'Pendiente';
                }
            }
        }
    }

    // Initialize status badges
    document.querySelectorAll('#documentsTableBody tr[data-type]').forEach(row => {
        const docType = row.getAttribute('data-type');
        updateDocumentStatus(docType, true);
    });
});
</script>
@endpush
