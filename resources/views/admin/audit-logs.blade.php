<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Logs - Panel de Administración</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f8fafc;
            color: #1f2937;
            line-height: 1.6;
        }

        /* Include admin header styles from dashboard */
        .admin-header {
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
            color: white;
            padding: 1rem 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .admin-header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .admin-logo i {
            font-size: 1.5rem;
            color: #60a5fa;
        }

        .admin-title {
            font-size: 1.25rem;
            font-weight: 700;
        }

        .admin-nav {
            display: flex;
            gap: 1rem;
        }

        .admin-nav a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: all 0.2s;
            font-weight: 500;
        }

        .admin-nav a:hover, .admin-nav a.active {
            background: rgba(255,255,255,0.1);
        }

        /* Main Layout */
        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border: 1px solid #e5e7eb;
        }

        .page-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .page-description {
            color: #6b7280;
            font-size: 1rem;
        }

        /* Filters Section */
        .filters-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border: 1px solid #e5e7eb;
        }

        .filters-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .filters-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filters-toggle {
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .filters-toggle:hover {
            background: #f3f4f6;
            color: #374151;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .filters-grid.collapsed {
            display: none;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
        }

        .form-control {
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-success:hover {
            background: #059669;
        }

        /* Statistics Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 1px solid #e5e7eb;
        }

        .stat-card-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 0.5rem;
            letter-spacing: 0.5px;
        }

        .stat-card-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
        }

        /* Data Table */
        .table-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border: 1px solid #e5e7eb;
        }

        .table-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .table-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .table-actions {
            display: flex;
            gap: 0.75rem;
        }

        /* Custom DataTable Styling */
        .dataTables_wrapper {
            font-size: 0.875rem;
        }

        .dataTables_length select,
        .dataTables_filter input {
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.875rem;
        }

        .dataTables_filter input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        table.dataTable {
            border-collapse: collapse;
            width: 100%;
        }

        table.dataTable thead th {
            background: #f9fafb;
            padding: 1rem;
            font-weight: 600;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table.dataTable tbody td {
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }

        table.dataTable tbody tr:hover {
            background: #f9fafb;
        }

        /* Status Badges */
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge.success {
            background: #d1fae5;
            color: #065f46;
        }

        .status-badge.failure {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-badge.pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge.in-progress {
            background: #dbeafe;
            color: #1e40af;
        }

        /* Event Type Badges */
        .event-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
            background: #f3f4f6;
            color: #374151;
        }

        .event-badge.registration { background: #dbeafe; color: #1e40af; }
        .event-badge.curp { background: #d1fae5; color: #065f46; }
        .event-badge.face { background: #fce7f3; color: #be185d; }
        .event-badge.admin { background: #f3e8ff; color: #7c3aed; }
        .event-badge.login { background: #fef3c7; color: #92400e; }

        /* Loading States */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loading-overlay.hidden {
            display: none;
        }

        .loading-content {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            text-align: center;
        }

        .spinner {
            width: 32px;
            height: 32px;
            border: 3px solid #e5e7eb;
            border-top: 3px solid #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .admin-container {
                padding: 1rem;
            }

            .filters-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }

            .table-card {
                padding: 1rem;
            }

            .admin-header-content {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .admin-nav {
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <header class="admin-header">
        <div class="admin-header-content">
            <div class="admin-logo">
                <i class="fas fa-shield-alt"></i>
                <div>
                    <div class="admin-title">Panel de Administración</div>
                    <div style="font-size: 0.75rem; opacity: 0.8;">Sistema MARINA</div>
                </div>
            </div>
            <nav class="admin-nav">
                <a href="/admin/dashboard"><i class="fas fa-chart-line"></i> Dashboard</a>
                <a href="/admin/audit-logs" class="active"><i class="fas fa-clipboard-list"></i> Audit Logs</a>
                <a href="/admin/users"><i class="fas fa-users"></i> Usuarios</a>
                <a href="/admin/settings"><i class="fas fa-cog"></i> Configuración</a>
                <a href="/registro"><i class="fas fa-arrow-left"></i> Volver</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <div class="admin-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-clipboard-list"></i>
                Audit Logs
            </h1>
            <p class="page-description">
                Monitoreo completo de eventos de seguridad y registro del sistema MARINA
            </p>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-title">Total de Eventos</div>
                <div class="stat-card-value">{{ $statistics['total_events'] ?? 0 }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-title">Eventos Hoy</div>
                <div class="stat-card-value">{{ $statistics['today_events'] ?? 0 }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-title">Tasa de Éxito</div>
                <div class="stat-card-value">{{ $statistics['success_rate'] ?? 0 }}%</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-title">Fallos Recientes</div>
                <div class="stat-card-value">{{ $statistics['recent_failures'] ?? 0 }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-title">Usuarios Únicos</div>
                <div class="stat-card-value">{{ $statistics['unique_users'] ?? 0 }}</div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="filters-card">
            <div class="filters-header">
                <div class="filters-title">
                    <i class="fas fa-filter"></i>
                    Filtros de Búsqueda
                </div>
                <button class="filters-toggle" onclick="toggleFilters()">
                    <i class="fas fa-chevron-up" id="filtersIcon"></i>
                </button>
            </div>
            
            <form class="filters-grid" id="filtersForm" method="GET" action="/admin/audit-logs">
                <div class="form-group">
                    <label class="form-label">Tipo de Evento</label>
                    <select class="form-control" name="event_type">
                        <option value="">Todos los eventos</option>
                        <option value="registration_started" {{ $eventType == 'registration_started' ? 'selected' : '' }}>Registro Iniciado</option>
                        <option value="curp_verification_success" {{ $eventType == 'curp_verification_success' ? 'selected' : '' }}>CURP Verificado</option>
                        <option value="curp_verification_failure" {{ $eventType == 'curp_verification_failure' ? 'selected' : '' }}>CURP Fallido</option>
                        <option value="face_matching_success" {{ $eventType == 'face_matching_success' ? 'selected' : '' }}>Verificación Facial Exitosa</option>
                        <option value="face_matching_failure" {{ $eventType == 'face_matching_failure' ? 'selected' : '' }}>Verificación Facial Fallida</option>
                        <option value="account_creation_completed" {{ $eventType == 'account_creation_completed' ? 'selected' : '' }}>Cuenta Creada</option>
                        <option value="admin_access" {{ $eventType == 'admin_access' ? 'selected' : '' }}>Acceso Admin</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Estado</label>
                    <select class="form-control" name="status">
                        <option value="">Todos los estados</option>
                        <option value="success" {{ $status == 'success' ? 'selected' : '' }}>Exitoso</option>
                        <option value="failure" {{ $status == 'failure' ? 'selected' : '' }}>Fallido</option>
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="in_progress" {{ $status == 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Usuario ID</label>
                    <input type="text" class="form-control" name="user_id" value="{{ $userId }}" placeholder="CURP, email, etc.">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Fecha Desde</label>
                    <input type="date" class="form-control" name="date_from" value="{{ $dateFrom }}">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Fecha Hasta</label>
                    <input type="date" class="form-control" name="date_to" value="{{ $dateTo }}">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Buscar</label>
                    <input type="text" class="form-control" name="search" value="{{ $search }}" placeholder="IP, session, etc.">
                </div>
                
                <div class="form-group" style="display: flex; align-items: end; gap: 0.75rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                        Aplicar Filtros
                    </button>
                    <a href="/admin/audit-logs" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Limpiar
                    </a>
                </div>
            </form>
        </div>

        <!-- Data Table -->
        <div class="table-card">
            <div class="table-header">
                <div class="table-title">
                    <i class="fas fa-table"></i>
                    Registros de Auditoría
                    <span style="font-size: 0.75rem; font-weight: 400; color: #6b7280; margin-left: 0.5rem;">
                        (Mostrando datos simulados)
                    </span>
                </div>
                <div class="table-actions">
                    <a href="/admin/audit-logs/export" class="btn btn-success">
                        <i class="fas fa-download"></i>
                        Exportar CSV
                    </a>
                    <button class="btn btn-secondary" onclick="refreshTable()">
                        <i class="fas fa-sync-alt"></i>
                        Actualizar
                    </button>
                </div>
            </div>
            
            <table id="auditLogsTable" class="table table-striped" style="width: 100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tipo de Evento</th>
                        <th>Usuario</th>
                        <th>Estado</th>
                        <th>IP</th>
                        <th>Fecha</th>
                        <th>Confianza</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded via AJAX -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay hidden" id="loadingOverlay">
        <div class="loading-content">
            <div class="spinner"></div>
            <p>Cargando datos de auditoría...</p>
        </div>
    </div>

    <script>
        // Global variables
        let auditTable = null;
        let filtersCollapsed = false;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            initializeDataTable();
        });

        function initializeDataTable() {
            auditTable = $('#auditLogsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/admin/api/audit-logs-data',
                    type: 'GET',
                    data: function(d) {
                        // Add filter parameters
                        const form = new FormData(document.getElementById('filtersForm'));
                        for (let [key, value] of form.entries()) {
                            if (value) {
                                d[key] = value;
                            }
                        }
                        return d;
                    }
                },
                columns: [
                    { data: 'id', name: 'id', width: '60px' },
                    { 
                        data: 'event_type', 
                        name: 'event_type',
                        render: function(data) {
                            const eventType = data.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                            let badgeClass = 'event-badge';
                            if (data.includes('registration')) badgeClass += ' registration';
                            else if (data.includes('curp')) badgeClass += ' curp';
                            else if (data.includes('face')) badgeClass += ' face';
                            else if (data.includes('admin')) badgeClass += ' admin';
                            else if (data.includes('login')) badgeClass += ' login';
                            
                            return `<span class="${badgeClass}">${eventType}</span>`;
                        }
                    },
                    { data: 'user_id', name: 'user_id' },
                    { 
                        data: 'status', 
                        name: 'status',
                        render: function(data) {
                            const statusClass = data === 'success' ? 'success' : 
                                              data === 'failure' ? 'failure' : 
                                              data === 'pending' ? 'pending' : 'in-progress';
                            return `<span class="status-badge ${statusClass}">${data}</span>`;
                        }
                    },
                    { data: 'ip_address', name: 'ip_address' },
                    { 
                        data: 'created_at', 
                        name: 'created_at',
                        render: function(data) {
                            return new Date(data).toLocaleString('es-ES');
                        }
                    },
                    { 
                        data: 'confidence_score', 
                        name: 'confidence_score',
                        render: function(data) {
                            return data ? `${data}%` : 'N/A';
                        }
                    },
                    {
                        data: 'id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return `
                                <button class="btn btn-sm btn-secondary" onclick="viewLogDetails(${data})" title="Ver Detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                            `;
                        }
                    }
                ],
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                order: [[5, 'desc']], // Order by date descending
                language: {
                    processing: "Procesando...",
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                    infoFiltered: "(filtrado de un total de _MAX_ registros)",
                    loadingRecords: "Cargando...",
                    zeroRecords: "No se encontraron registros coincidentes",
                    emptyTable: "No hay datos disponibles en la tabla",
                    paginate: {
                        first: "Primero",
                        previous: "Anterior",
                        next: "Siguiente",
                        last: "Último"
                    }
                }
            });
        }

        function toggleFilters() {
            const filtersForm = document.getElementById('filtersForm');
            const filtersIcon = document.getElementById('filtersIcon');
            
            filtersCollapsed = !filtersCollapsed;
            
            if (filtersCollapsed) {
                filtersForm.classList.add('collapsed');
                filtersIcon.className = 'fas fa-chevron-down';
            } else {
                filtersForm.classList.remove('collapsed');
                filtersIcon.className = 'fas fa-chevron-up';
            }
        }

        function refreshTable() {
            if (auditTable) {
                auditTable.ajax.reload();
            }
        }

        function applyFilters() {
            if (auditTable) {
                auditTable.ajax.reload();
            }
        }

        function viewLogDetails(logId) {
            // Show detailed view of audit log
            alert(`Mostrar detalles del log ID: ${logId}\n\nEn una implementación real, esto abriría un modal con información detallada del evento de auditoría.`);
        }

        // Auto-refresh every 60 seconds
        setInterval(() => {
            if (auditTable) {
                auditTable.ajax.reload(null, false); // Reload without resetting pagination
            }
        }, 60000);

        // Handle filter form submission
        document.getElementById('filtersForm').addEventListener('submit', function(e) {
            e.preventDefault();
            applyFilters();
        });
    </script>
</body>
</html>