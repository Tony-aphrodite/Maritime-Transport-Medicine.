<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - MARINA</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            overflow-x: hidden;
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
            position: relative;
            overflow: hidden;
        }

        .admin-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="0.5" fill="%23ffffff" opacity="0.03"/><circle cx="75" cy="75" r="0.5" fill="%23ffffff" opacity="0.03"/><circle cx="50" cy="10" r="0.3" fill="%23ffffff" opacity="0.02"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            pointer-events: none;
        }

        .admin-header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .admin-logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .admin-logo i {
            font-size: 1.5rem;
            background: linear-gradient(135deg, #BBE1FA 0%, #ffffff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
        }

        .admin-title {
            font-size: 1.25rem;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .admin-nav {
            display: flex;
            gap: 1rem;
        }

        .admin-nav a {
            color: white;
            text-decoration: none;
            padding: 0.75rem 1.25rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .admin-nav a:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .admin-nav a.active {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        /* Main Layout */
        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            min-height: calc(100vh - 80px);
        }

        /* Dashboard Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        /* Stats Cards */
        .stats-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #0F4C75, #3282B8, #0F4C75);
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            background: rgba(255, 255, 255, 0.95);
        }

        .stats-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .stats-card-title {
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            color: #6b7280;
            letter-spacing: 0.5px;
        }

        .stats-card-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
        }

        .stats-card-icon.blue { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .stats-card-icon.green { background: linear-gradient(135deg, #10b981, #059669); }
        .stats-card-icon.yellow { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .stats-card-icon.red { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .stats-card-icon.purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }

        .stats-card-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .stats-card-change {
            font-size: 0.875rem;
            font-weight: 500;
        }

        .stats-card-change.positive { color: #059669; }
        .stats-card-change.negative { color: #dc2626; }
        .stats-card-change.neutral { color: #6b7280; }

        /* Charts Section */
        .charts-section {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .chart-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            border: 1px solid rgba(255,255,255,0.3);
            position: relative;
            overflow: hidden;
        }

        .chart-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #0F4C75, #3282B8, #0F4C75);
        }

        .chart-card-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        /* Recent Activities */
        .activities-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .activity-item {
            display: flex;
            align-items: start;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            color: white;
            flex-shrink: 0;
        }

        .activity-content {
            flex: 1;
        }

        .activity-message {
            font-weight: 500;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .activity-meta {
            font-size: 0.75rem;
            color: #6b7280;
        }

        .activity-time {
            font-size: 0.75rem;
            color: #9ca3af;
            font-weight: 500;
        }

        /* Data Table */
        .data-table-container {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            border: 1px solid rgba(255,255,255,0.3);
            position: relative;
            overflow: hidden;
        }

        .data-table-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #0F4C75, #3282B8, #0F4C75);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .table-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1f2937;
        }

        .table-actions {
            display: flex;
            gap: 0.75rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 500;
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
            background: linear-gradient(135deg, #0F4C75, #3282B8);
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(15, 76, 117, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0d3e5f, #2a6ba0);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(15, 76, 117, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.8);
            color: #374151;
            border: 2px solid rgba(15, 76, 117, 0.2);
            backdrop-filter: blur(5px);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.95);
            border-color: rgba(15, 76, 117, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            background: #f9fafb;
            padding: 0.75rem 1rem;
            text-align: left;
            font-weight: 600;
            color: #374151;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e5e7eb;
        }

        .data-table td {
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }

        .data-table tr:hover {
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

        /* Loading States */
        .loading {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            color: #6b7280;
        }

        .spinner {
            width: 24px;
            height: 24px;
            border: 2px solid #e5e7eb;
            border-top: 2px solid #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 0.5rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .charts-section {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .admin-container {
                padding: 1rem;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
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
    @include('admin.partials.alert-system')

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
                <a href="/admin/dashboard" class="active"><i class="fas fa-chart-line"></i> Dashboard</a>
                <a href="/admin/audit-logs"><i class="fas fa-clipboard-list"></i> Audit Logs</a>
                <a href="/admin/users"><i class="fas fa-users"></i> Usuarios</a>
                <a href="/admin/settings"><i class="fas fa-cog"></i> Configuración</a>
                <a href="/admin/logout" style="background: rgba(239, 68, 68, 0.2); color: #fca5a5;"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
                <a href="/"><i class="fas fa-arrow-left"></i> Volver</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <div class="admin-container">
        <!-- Dashboard Stats -->
        <div class="dashboard-grid" id="dashboardStats">
            <div class="stats-card">
                <div class="stats-card-header">
                    <div class="stats-card-title">Registros Hoy</div>
                    <div class="stats-card-icon blue">
                        <i class="fas fa-user-plus"></i>
                    </div>
                </div>
                <div class="stats-card-value" id="todayRegistrations">--</div>
                <div class="stats-card-change positive">
                    <i class="fas fa-arrow-up"></i> +12% desde ayer
                </div>
            </div>

            <div class="stats-card">
                <div class="stats-card-header">
                    <div class="stats-card-title">Verificaciones CURP</div>
                    <div class="stats-card-icon green">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="stats-card-value" id="curpSuccess">--</div>
                <div class="stats-card-change positive" id="curpSuccessRate">
                    <i class="fas fa-percentage"></i> --% éxito
                </div>
            </div>

            <div class="stats-card">
                <div class="stats-card-header">
                    <div class="stats-card-title">Verificaciones Faciales</div>
                    <div class="stats-card-icon purple">
                        <i class="fas fa-camera"></i>
                    </div>
                </div>
                <div class="stats-card-value" id="faceSuccess">--</div>
                <div class="stats-card-change positive" id="faceSuccessRate">
                    <i class="fas fa-percentage"></i> --% éxito
                </div>
            </div>

            <div class="stats-card">
                <div class="stats-card-header">
                    <div class="stats-card-title">Fallos Hoy</div>
                    <div class="stats-card-icon red">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
                <div class="stats-card-value" id="failedAttempts">--</div>
                <div class="stats-card-change negative">
                    <i class="fas fa-arrow-down"></i> -5% desde ayer
                </div>
            </div>

            <div class="stats-card">
                <div class="stats-card-header">
                    <div class="stats-card-title">Esta Semana</div>
                    <div class="stats-card-icon yellow">
                        <i class="fas fa-calendar-week"></i>
                    </div>
                </div>
                <div class="stats-card-value" id="weekRegistrations">--</div>
                <div class="stats-card-change positive">
                    <i class="fas fa-arrow-up"></i> +8% semanal
                </div>
            </div>

            <div class="stats-card">
                <div class="stats-card-header">
                    <div class="stats-card-title">Este Mes</div>
                    <div class="stats-card-icon blue">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
                <div class="stats-card-value" id="monthRegistrations">--</div>
                <div class="stats-card-change positive">
                    <i class="fas fa-arrow-up"></i> +15% mensual
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
            <!-- Registration Chart -->
            <div class="chart-card">
                <div class="chart-card-title">
                    <i class="fas fa-chart-bar"></i>
                    Registros por Hora (Hoy)
                </div>
                <div class="chart-container">
                    <canvas id="registrationChart"></canvas>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="chart-card">
                <div class="chart-card-title">
                    <i class="fas fa-clock"></i>
                    Actividad Reciente
                </div>
                <div class="activities-list" id="recentActivities">
                    <div class="loading">
                        <div class="spinner"></div>
                        Cargando actividades...
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Audit Logs Table -->
        <div class="data-table-container">
            <div class="table-header">
                <div class="table-title">
                    <i class="fas fa-clipboard-list"></i>
                    Logs de Auditoría Recientes
                </div>
                <div class="table-actions">
                    <a href="/admin/audit-logs" class="btn btn-primary">
                        <i class="fas fa-external-link-alt"></i>
                        Ver Todos
                    </a>
                    <button class="btn btn-secondary" onclick="refreshLogs()">
                        <i class="fas fa-sync-alt"></i>
                        Actualizar
                    </button>
                </div>
            </div>
            
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Evento</th>
                            <th>Usuario</th>
                            <th>Estado</th>
                            <th>IP</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody id="recentLogsTable">
                        <tr>
                            <td colspan="5" class="loading">
                                <div class="spinner"></div>
                                Cargando logs...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Global variables
        let registrationChart = null;
        let refreshInterval = null;

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            initializeDashboard();
            startAutoRefresh();
            // Alert system is initialized by the shared component
        });

        async function initializeDashboard() {
            try {
                // Load dashboard statistics
                await loadDashboardStats();
                
                // Initialize chart
                initializeRegistrationChart();
                
                // Load recent activities
                loadRecentActivities();
                
                // Load recent audit logs
                loadRecentAuditLogs();
                
            } catch (error) {
                console.error('Error initializing dashboard:', error);
            }
        }

        async function loadDashboardStats() {
            try {
                const response = await fetch('/admin/api/dashboard-stats');
                
                if (!response.ok) {
                    if (response.status === 401) {
                        // Authentication error - redirect to login
                        alert('Sesión expirada. Redirigiendo al login...');
                        window.location.href = '/login';
                        return;
                    }
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (data.error) {
                    if (data.error === 'Authentication required') {
                        alert('Sesión expirada. Redirigiendo al login...');
                        window.location.href = '/login';
                        return;
                    }
                    throw new Error(data.error);
                }
                
                // Update stat cards
                document.getElementById('todayRegistrations').textContent = data.total_registrations_today;
                document.getElementById('weekRegistrations').textContent = data.total_registrations_week;
                document.getElementById('monthRegistrations').textContent = data.total_registrations_month;
                document.getElementById('failedAttempts').textContent = data.total_failed_attempts_today;
                document.getElementById('curpSuccess').textContent = data.verification_breakdown.curp_success;
                document.getElementById('faceSuccess').textContent = data.verification_breakdown.face_success;
                document.getElementById('curpSuccessRate').innerHTML = `<i class="fas fa-percentage"></i> ${data.curp_verification_success_rate}% éxito`;
                document.getElementById('faceSuccessRate').innerHTML = `<i class="fas fa-percentage"></i> ${data.face_verification_success_rate}% éxito`;
                
                return data;
                
            } catch (error) {
                console.error('Error loading dashboard stats:', error);
                // Show error state
                document.querySelectorAll('.stats-card-value').forEach(el => {
                    el.textContent = 'Error';
                });
            }
        }

        function initializeRegistrationChart() {
            const ctx = document.getElementById('registrationChart').getContext('2d');
            
            // Sample data - in real implementation, this would come from API
            const hours = Array.from({length: 24}, (_, i) => String(i).padStart(2, '0') + ':00');
            const registrationData = [2, 1, 0, 1, 3, 7, 12, 15, 18, 22, 25, 23, 28, 26, 24, 27, 29, 25, 22, 18, 15, 12, 8, 5];
            
            registrationChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: hours,
                    datasets: [{
                        label: 'Registros',
                        data: registrationData,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f3f4f6'
                            }
                        },
                        x: {
                            grid: {
                                color: '#f3f4f6'
                            }
                        }
                    }
                }
            });
        }

        async function loadRecentActivities() {
            try {
                const response = await fetch('/admin/api/dashboard-stats');
                
                if (!response.ok) {
                    if (response.status === 401) {
                        console.log('Authentication error in recent activities - already handled by main stats');
                        return;
                    }
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (data.error) {
                    if (data.error === 'Authentication required') {
                        console.log('Authentication error in recent activities - already handled by main stats');
                        return;
                    }
                    throw new Error(data.error);
                }
                
                if (data.recent_activities) {
                    displayRecentActivities(data.recent_activities);
                }
                
            } catch (error) {
                console.error('Error loading recent activities:', error);
                document.getElementById('recentActivities').innerHTML = '<p style="text-align: center; color: #ef4444;">Error cargando actividades</p>';
            }
        }

        function displayRecentActivities(activities) {
            const container = document.getElementById('recentActivities');
            
            if (activities.length === 0) {
                container.innerHTML = '<p style="text-align: center; color: #6b7280;">No hay actividades recientes</p>';
                return;
            }
            
            const html = activities.map(activity => {
                const iconColor = activity.status === 'success' ? 'background: #10b981;' : 
                                 activity.status === 'failure' ? 'background: #ef4444;' : 
                                 'background: #6b7280;';
                                 
                const icon = activity.type.includes('curp') ? 'fas fa-id-card' :
                           activity.type.includes('face') ? 'fas fa-camera' :
                           activity.type.includes('admin') ? 'fas fa-shield-alt' :
                           activity.type.includes('account') ? 'fas fa-user-check' :
                           'fas fa-circle';
                
                return `
                    <div class="activity-item">
                        <div class="activity-icon" style="${iconColor}">
                            <i class="${icon}"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-message">${activity.message}</div>
                            <div class="activity-meta">
                                Usuario: ${activity.user_id} • Estado: ${activity.status}
                            </div>
                        </div>
                        <div class="activity-time">${activity.timestamp}</div>
                    </div>
                `;
            }).join('');
            
            container.innerHTML = html;
        }

        async function loadRecentAuditLogs() {
            try {
                const response = await fetch('/admin/api/audit-logs-data?length=5');
                const data = await response.json();
                
                if (data.data) {
                    displayRecentAuditLogs(data.data);
                }
                
            } catch (error) {
                console.error('Error loading recent audit logs:', error);
                document.getElementById('recentLogsTable').innerHTML = '<tr><td colspan="5" style="text-align: center; color: #ef4444;">Error cargando logs</td></tr>';
            }
        }

        function displayRecentAuditLogs(logs) {
            const tbody = document.getElementById('recentLogsTable');
            
            if (logs.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; color: #6b7280;">No hay logs recientes</td></tr>';
                return;
            }
            
            const html = logs.map(log => {
                const statusClass = log.status === 'success' ? 'success' : 
                                  log.status === 'failure' ? 'failure' : 
                                  log.status === 'pending' ? 'pending' : 'in-progress';
                
                const eventTypeDisplay = log.event_type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                
                return `
                    <tr>
                        <td>${eventTypeDisplay}</td>
                        <td>${log.user_id || 'N/A'}</td>
                        <td><span class="status-badge ${statusClass}">${log.status}</span></td>
                        <td>${log.ip_address}</td>
                        <td>${new Date(log.created_at).toLocaleString('es-ES')}</td>
                    </tr>
                `;
            }).join('');
            
            tbody.innerHTML = html;
        }

        function refreshLogs() {
            loadRecentAuditLogs();
        }

        function startAutoRefresh() {
            // Refresh data every 30 seconds
            refreshInterval = setInterval(() => {
                loadDashboardStats();
                loadRecentActivities();
                loadRecentAuditLogs();
            }, 30000);
        }

        // Enhanced refresh interval
        function startAutoRefresh() {
            refreshInterval = setInterval(() => {
                loadDashboardStats();
                loadRecentActivities();
                loadRecentAuditLogs();
            }, 30000);
        }

        // Cleanup interval on page unload
        window.addEventListener('beforeunload', function() {
            if (refreshInterval) {
                clearInterval(refreshInterval);
            }
            // Alert system cleanup is handled by shared component
        });
    </script>
</body>
</html>