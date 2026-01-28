@extends('layouts.dashboard')

@section('title', 'Agendar Cita - Subir Estudios Medicos')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/appointments.css') }}">
@endpush

@section('content')
<section class="booking-container">
    <!-- Back Navigation -->
    <div class="back-nav">
        <a href="{{ route('appointments.step1') }}" class="btn-back-link">
            <i class="fas fa-arrow-left"></i> Volver a calendario
        </a>
    </div>

    <!-- Stepper -->
    <div class="stepper">
        <div class="step completed"><span><i class="fas fa-check"></i></span><p>Fecha</p></div>
        <div class="step active"><span>2</span><p>Archivos</p></div>
        <div class="step"><span>3</span><p>Salud</p></div>
        <div class="step"><span>4</span><p>Confirma</p></div>
        <div class="step"><span>5</span><p>Pago</p></div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
            {{ session('error') }}
        </div>
    @endif

    <div class="files-layout">
        <div class="card-white">
            <div class="card-header-flex">
                <div>
                    <h3>Carga de Documentacion Medica</h3>
                    <p class="subtitle">Por favor, adjunte sus analisis clinicos recientes (Sangre, Orina, ECG, etc.) para la revision del medico.</p>
                </div>
                <div class="file-upload-wrapper">
                    <input type="file" id="fileInput" accept=".pdf,.jpg,.jpeg,.png" multiple hidden>
                    <button type="button" class="btn-upload" onclick="document.getElementById('fileInput').click()">
                        <i class="fas fa-cloud-upload-alt"></i> Subir Archivo
                    </button>
                </div>
            </div>

            <!-- Document Type Selection Modal -->
            <div id="documentTypeModal" class="modal-overlay">
                <div class="modal-content">
                    <h3><i class="fas fa-file-medical" style="color: var(--accent-gold);"></i> Tipo de Estudio</h3>
                    <p style="margin-bottom: 1rem; color: #666;">Seleccione el tipo de estudio que esta subiendo:</p>
                    <p id="uploadingFileName" style="font-weight: 600; margin-bottom: 1rem;"></p>
                    <select id="documentTypeSelect" class="modal-select">
                        <option value="blood_test">Biometria Hematica</option>
                        <option value="chemistry">Quimica Sanguinea</option>
                        <option value="urine_test">Examen General de Orina</option>
                        <option value="chest_xray">Radiografia de Torax</option>
                        <option value="ecg">Electrocardiograma (ECG)</option>
                        <option value="vision_test">Examen de Vista</option>
                        <option value="audiometry">Audiometria</option>
                        <option value="other_medical">Otros Estudios</option>
                    </select>
                    <div class="modal-buttons">
                        <button type="button" id="cancelUpload" class="btn-modal-cancel">Cancelar</button>
                        <button type="button" id="confirmUpload" class="btn-modal-confirm">Subir</button>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <table class="files-table">
                    <thead>
                        <tr>
                            <th>Nombre del Documento</th>
                            <th>Tipo / Categoria</th>
                            <th>Tamano</th>
                            <th>Estado</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody id="documentsTableBody">
                        @forelse($documents as $document)
                        <tr data-id="{{ $document->id }}" data-type="{{ $document->document_type }}">
                            <td data-label="Archivo">
                                @if(str_contains($document->mime_type ?? '', 'pdf'))
                                    <i class="far fa-file-pdf" style="color: #dc3545;"></i>
                                @else
                                    <i class="far fa-file-image" style="color: #28a745;"></i>
                                @endif
                                {{ $document->original_name }}
                            </td>
                            <td data-label="Categoria">{{ $document->document_type_label }}</td>
                            <td data-label="Tamano">{{ $document->formatted_size }}</td>
                            <td data-label="Estado"><span class="status-pill green"><i class="fas fa-check-circle"></i> Listo</span></td>
                            <td data-label="Accion">
                                <button type="button" class="btn-delete" onclick="deleteDocument({{ $document->id }})">
                                    <i class="fas fa-trash"></i>
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
            </div>

            <div class="file-requirements">
                <p><i class="fas fa-info-circle"></i> Formatos aceptados: <strong>PDF, JPG, PNG</strong>. Tamano maximo por archivo: <strong>10MB</strong>.</p>
            </div>

            <form action="{{ route('appointments.step2.process') }}" method="POST" id="step2Form">
                @csrf
                <div class="action-footer">
                    <a href="{{ route('appointments.step1') }}" class="btn-back-gold">
                        <i class="fas fa-arrow-left"></i> Atras
                    </a>
                    <button type="submit" class="btn-primary-gold">
                        Continuar a Declaracion <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('fileInput');
    const modal = document.getElementById('documentTypeModal');
    const documentTypeSelect = document.getElementById('documentTypeSelect');
    const uploadingFileName = document.getElementById('uploadingFileName');
    const documentsTableBody = document.getElementById('documentsTableBody');
    const noDocumentsRow = document.getElementById('noDocumentsRow');

    let pendingFile = null;

    // File input change handler
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleFile(e.target.files[0]);
        }
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
            <td><span class="status-pill" style="background: #fff3cd; color: #856404;">Subiendo...</span></td>
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
            <td data-label="Archivo"><i class="far ${fileIcon}" style="color: ${fileIconColor};"></i> ${doc.original_name}</td>
            <td data-label="Categoria">${typeLabels[doc.document_type] || doc.document_type}</td>
            <td data-label="Tamano">${formatFileSize(doc.file_size)}</td>
            <td data-label="Estado"><span class="status-pill green"><i class="fas fa-check-circle"></i> Listo</span></td>
            <td data-label="Accion"><button type="button" class="btn-delete" onclick="deleteDocument(${doc.id})"><i class="fas fa-trash"></i></button></td>
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
});
</script>
@endpush
