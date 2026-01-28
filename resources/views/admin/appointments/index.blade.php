<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Citas - Panel de Administracion</title>
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
            background-attachment: fixed;
            color: #2d3748;
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Admin Header */
        .admin-header {
            background: linear-gradient(135deg, #0F4C75 0%, #3282B8 100%);
            color: white;
            padding: 1.5rem 2rem;
            box-shadow: 0 8px 32px rgba(15, 76, 117, 0.3);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .admin-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
        }

        .admin-logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-logo i {
            font-size: 2rem;
            color: #BBE1FA;
        }

        .admin-title {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .admin-nav {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .admin-nav a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
        }

        .admin-nav a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .admin-nav a.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        /* Content */
        .content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            border: 1px solid rgba(255,255,255,0.3);
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: #6b7280;
            font-size: 1.125rem;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: 1px solid rgba(255,255,255,0.3);
            text-align: center;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.25rem;
            color: white;
        }

        .stat-icon.blue { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .stat-icon.yellow { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .stat-icon.green { background: linear-gradient(135deg, #10b981, #059669); }
        .stat-icon.purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
        .stat-icon.red { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .stat-icon.cyan { background: linear-gradient(135deg, #06b6d4, #0891b2); }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 500;
        }

        /* Filters */
        .filters-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: 1px solid rgba(255,255,255,0.3);
        }

        .filters-form {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: flex-end;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .filter-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .filter-group input,
        .filter-group select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.3s;
            background: white;
        }

        .filter-group input:focus,
        .filter-group select:focus {
            outline: none;
            border-color: #0F4C75;
            box-shadow: 0 0 0 3px rgba(15, 76, 117, 0.1);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0F4C75, #3282B8);
            color: white;
            box-shadow: 0 4px 15px rgba(15, 76, 117, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(15, 76, 117, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.8);
            color: #374151;
            border: 2px solid rgba(15, 76, 117, 0.2);
        }

        .btn-secondary:hover {
            background: white;
            border-color: rgba(15, 76, 117, 0.3);
        }

        /* Table */
        .table-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            border: 1px solid rgba(255,255,255,0.3);
            overflow: hidden;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .table-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            background: #f9fafb;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #374151;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e5e7eb;
            white-space: nowrap;
        }

        .data-table td {
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }

        .data-table tr:hover {
            background: #f9fafb;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            color: #1f2937;
        }

        .user-email {
            font-size: 0.875rem;
            color: #6b7280;
        }

        /* Status Badges */
        .status-badge {
            padding: 0.375rem 0.875rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }

        .status-badge.pending_payment {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge.confirmed {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-badge.completed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-badge.cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            font-size: 0.875rem;
        }

        .action-btn.view {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .action-btn.view:hover {
            background: #bfdbfe;
        }

        .action-btn.confirm {
            background: #d1fae5;
            color: #059669;
        }

        .action-btn.confirm:hover {
            background: #a7f3d0;
        }

        .action-btn.complete {
            background: #c7d2fe;
            color: #4f46e5;
        }

        .action-btn.complete:hover {
            background: #a5b4fc;
        }

        .action-btn.cancel {
            background: #fee2e2;
            color: #dc2626;
        }

        .action-btn.cancel:hover {
            background: #fecaca;
        }

        .action-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Pagination */
        .pagination-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .pagination-info {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .pagination {
            display: flex;
            gap: 0.25rem;
        }

        .pagination a,
        .pagination span {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .pagination a {
            color: #374151;
            background: white;
            border: 1px solid #e5e7eb;
        }

        .pagination a:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
        }

        .pagination span.current {
            background: linear-gradient(135deg, #0F4C75, #3282B8);
            color: white;
            border: 1px solid transparent;
        }

        .pagination span.disabled {
            color: #9ca3af;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
        }

        .empty-state i {
            font-size: 4rem;
            color: #d1d5db;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            color: #6b7280;
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #9ca3af;
        }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            text-align: center;
        }

        .modal-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
        }

        .modal-icon.warning {
            background: #fef3c7;
            color: #f59e0b;
        }

        .modal-icon.danger {
            background: #fee2e2;
            color: #ef4444;
        }

        .modal-icon.success {
            background: #d1fae5;
            color: #10b981;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .modal-message {
            color: #6b7280;
            margin-bottom: 1.5rem;
        }

        .modal-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        /* Toast notification */
        .toast {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            color: white;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 1001;
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .toast.success {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .toast.error {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .content {
                padding: 1rem;
            }

            .admin-header-content {
                flex-direction: column;
                gap: 1rem;
            }

            .admin-nav {
                justify-content: center;
            }

            .filters-form {
                flex-direction: column;
            }

            .filter-group {
                width: 100%;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    @include('admin.partials.alert-system')

    <!-- Admin Header -->
    <header class="admin-header">
        <div class="admin-header-content">
            <div class="admin-logo">
                <i class="fas fa-shield-alt"></i>
                <div>
                    <div class="admin-title">Panel de Administracion</div>
                    <div style="font-size: 0.75rem; opacity: 0.8;">Sistema MARINA</div>
                </div>
            </div>
            <nav class="admin-nav">
                <a href="/admin/dashboard"><i class="fas fa-chart-line"></i> Dashboard</a>
                <a href="/admin/appointments" class="active"><i class="fas fa-calendar-check"></i> Citas</a>
                <a href="/admin/audit-logs"><i class="fas fa-clipboard-list"></i> Audit Logs</a>
                <a href="/admin/users"><i class="fas fa-users"></i> Usuarios</a>
                <a href="/admin/settings"><i class="fas fa-cog"></i> Configuracion</a>
                <a href="/admin/logout" style="background: rgba(239, 68, 68, 0.2); color: #fca5a5;"><i class="fas fa-sign-out-alt"></i> Cerrar Sesion</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <div class="content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title"><i class="fas fa-calendar-check"></i> Gestion de Citas</h1>
            <p class="page-subtitle">Administrar y dar seguimiento a las citas medicas</p>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Citas</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon yellow">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value">{{ $stats['pending_payment'] }}</div>
                <div class="stat-label">Pendiente Pago</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon cyan">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-value">{{ $stats['confirmed'] }}</div>
                <div class="stat-label">Confirmadas</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-check-double"></i>
                </div>
                <div class="stat-value">{{ $stats['completed'] }}</div>
                <div class="stat-label">Completadas</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon red">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-value">{{ $stats['cancelled'] }}</div>
                <div class="stat-label">Canceladas</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stat-value">{{ $stats['today'] }}</div>
                <div class="stat-label">Hoy</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-card">
            <form method="GET" action="{{ route('admin.appointments.index') }}" class="filters-form">
                <div class="filter-group">
                    <label for="search">Buscar</label>
                    <input type="text" id="search" name="search" placeholder="Nombre, email, CURP..." value="{{ request('search') }}">
                </div>

                <div class="filter-group">
                    <label for="status">Estado</label>
                    <select id="status" name="status">
                        <option value="">Todos los estados</option>
                        <option value="pending_payment" {{ request('status') == 'pending_payment' ? 'selected' : '' }}>Pendiente de Pago</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmada</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completada</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="date_from">Fecha Desde</label>
                    <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}">
                </div>

                <div class="filter-group">
                    <label for="date_to">Fecha Hasta</label>
                    <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}">
                </div>

                <div class="filter-group" style="display: flex; gap: 0.5rem; min-width: auto;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="table-card">
            <div class="table-header">
                <h2 class="table-title"><i class="fas fa-list"></i> Lista de Citas</h2>
                <button class="btn btn-secondary" onclick="location.reload()">
                    <i class="fas fa-sync-alt"></i> Actualizar
                </button>
            </div>

            @if($appointments->count() > 0)
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Fecha Cita</th>
                                <th>Hora</th>
                                <th>Tipo Examen</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Creada</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                                <tr>
                                    <td><strong>#{{ $appointment->id }}</strong></td>
                                    <td>
                                        <div class="user-info">
                                            <span class="user-name">
                                                {{ $appointment->user->nombres ?? 'N/A' }}
                                                {{ $appointment->user->apellido_paterno ?? '' }}
                                            </span>
                                            <span class="user-email">{{ $appointment->user->email ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $appointment->appointment_date->format('d/m/Y') }}</td>
                                    <td>{{ $appointment->appointment_time }}</td>
                                    <td>{{ $appointment->exam_type_label }}</td>
                                    <td><strong>${{ number_format($appointment->total, 2) }}</strong></td>
                                    <td>
                                        <span class="status-badge {{ $appointment->status }}">
                                            @if($appointment->status == 'pending_payment')
                                                <i class="fas fa-clock"></i>
                                            @elseif($appointment->status == 'confirmed')
                                                <i class="fas fa-check"></i>
                                            @elseif($appointment->status == 'completed')
                                                <i class="fas fa-check-double"></i>
                                            @else
                                                <i class="fas fa-times"></i>
                                            @endif
                                            {{ $appointment->status_label }}
                                        </span>
                                    </td>
                                    <td>{{ $appointment->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.appointments.show', $appointment->id) }}" class="action-btn view" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($appointment->status == 'pending_payment')
                                                <button class="action-btn confirm" title="Confirmar pago" onclick="changeStatus({{ $appointment->id }}, 'confirmed')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="action-btn cancel" title="Cancelar" onclick="changeStatus({{ $appointment->id }}, 'cancelled')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @elseif($appointment->status == 'confirmed')
                                                <button class="action-btn complete" title="Marcar completada" onclick="changeStatus({{ $appointment->id }}, 'completed')">
                                                    <i class="fas fa-check-double"></i>
                                                </button>
                                                <button class="action-btn cancel" title="Cancelar" onclick="changeStatus({{ $appointment->id }}, 'cancelled')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @else
                                                <button class="action-btn view" disabled title="Sin acciones disponibles">
                                                    <i class="fas fa-lock"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        Mostrando {{ $appointments->firstItem() }} - {{ $appointments->lastItem() }} de {{ $appointments->total() }} citas
                    </div>
                    <div class="pagination">
                        @if($appointments->onFirstPage())
                            <span class="disabled"><i class="fas fa-chevron-left"></i></span>
                        @else
                            <a href="{{ $appointments->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
                        @endif

                        @foreach($appointments->getUrlRange(1, $appointments->lastPage()) as $page => $url)
                            @if($page == $appointments->currentPage())
                                <span class="current">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($appointments->hasMorePages())
                            <a href="{{ $appointments->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
                        @else
                            <span class="disabled"><i class="fas fa-chevron-right"></i></span>
                        @endif
                    </div>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <h3>No se encontraron citas</h3>
                    <p>No hay citas que coincidan con los filtros seleccionados</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal-overlay" id="confirmModal">
        <div class="modal-content">
            <div class="modal-icon warning" id="modalIcon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="modal-title" id="modalTitle">Confirmar Accion</h3>
            <p class="modal-message" id="modalMessage">Esta seguro de realizar esta accion?</p>
            <div class="modal-buttons">
                <button class="btn btn-secondary" onclick="closeModal()">Cancelar</button>
                <button class="btn btn-primary" id="confirmBtn" onclick="confirmAction()">Confirmar</button>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div class="toast" id="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toastMessage"></span>
    </div>

    <script>
        let pendingAction = null;

        function changeStatus(appointmentId, newStatus) {
            const statusMessages = {
                'confirmed': {
                    title: 'Confirmar Pago',
                    message: 'Esta seguro de confirmar el pago de esta cita? Se marcara como pagada.',
                    icon: 'success',
                    iconHtml: '<i class="fas fa-check-circle"></i>'
                },
                'completed': {
                    title: 'Completar Cita',
                    message: 'Esta seguro de marcar esta cita como completada?',
                    icon: 'success',
                    iconHtml: '<i class="fas fa-check-double"></i>'
                },
                'cancelled': {
                    title: 'Cancelar Cita',
                    message: 'Esta seguro de cancelar esta cita? Esta accion no se puede deshacer.',
                    icon: 'danger',
                    iconHtml: '<i class="fas fa-times-circle"></i>'
                }
            };

            const config = statusMessages[newStatus];
            if (!config) return;

            pendingAction = { appointmentId, newStatus };

            const modal = document.getElementById('confirmModal');
            const modalIcon = document.getElementById('modalIcon');
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');

            modalIcon.className = 'modal-icon ' + config.icon;
            modalIcon.innerHTML = config.iconHtml;
            modalTitle.textContent = config.title;
            modalMessage.textContent = config.message;

            modal.classList.add('active');
        }

        function closeModal() {
            document.getElementById('confirmModal').classList.remove('active');
            pendingAction = null;
        }

        async function confirmAction() {
            if (!pendingAction) return;

            const { appointmentId, newStatus } = pendingAction;
            closeModal();

            try {
                const response = await fetch(`/admin/appointments/${appointmentId}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ status: newStatus })
                });

                const data = await response.json();

                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message || 'Error al actualizar el estado', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error de conexion', 'error');
            }
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');

            toast.className = 'toast ' + type;
            toast.querySelector('i').className = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
            toastMessage.textContent = message;

            toast.classList.add('show');

            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        // Close modal on outside click
        document.getElementById('confirmModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>
