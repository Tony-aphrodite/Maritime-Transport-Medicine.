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
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            background-attachment: fixed;
            color: #2d3748;
            line-height: 1.6;
            min-height: 100vh;
        }

        .main-container {
            background: transparent;
            min-height: 100vh;
            margin-top: 0;
        }

        /* Maritime admin header */
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
            color: white;
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
            background: transparent;
        }

        .page-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #0F4C75, #3282B8, #0F4C75);
        }

        .page-title {
            font-size: 2.25rem;
            font-weight: 800;
            background: linear-gradient(135deg, #0F4C75 0%, #3282B8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .page-title i {
            background: linear-gradient(135deg, #0F4C75 0%, #3282B8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-description {
            color: #6b7280;
            font-size: 1rem;
        }

        /* Enhanced Filters Section */
        .filters-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .filters-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.12);
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

        .filter-buttons {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 0.5rem;
        }


        /* Enhanced Filter Button Styles */
        .filter-buttons .btn {
            padding: 1rem 2rem;
            font-weight: 700;
            font-size: 0.9rem;
            border-radius: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: none;
            min-width: 160px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .filter-buttons .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.6s;
        }

        .filter-buttons .btn:hover::before {
            left: 100%;
        }

        .filter-buttons .btn-primary {
            background: linear-gradient(135deg, #0F4C75 0%, #3282B8 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(15, 76, 117, 0.3);
        }

        .filter-buttons .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(15, 76, 117, 0.4);
            background: linear-gradient(135deg, #0d3e5f 0%, #2a6ba0 100%);
        }

        .filter-buttons .btn-primary:active {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(15, 76, 117, 0.5);
        }

        .filter-buttons .btn-secondary {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: #374151;
            border: 2px solid #e5e7eb;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .filter-buttons .btn-secondary:hover {
            transform: translateY(-3px);
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border-color: rgba(239, 68, 68, 0.3);
            box-shadow: 0 10px 30px rgba(239, 68, 68, 0.3);
        }

        .filter-buttons .btn-secondary:active {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(239, 68, 68, 0.4);
        }

        .filter-buttons .btn i {
            font-size: 1rem;
            transition: transform 0.3s ease;
        }

        .filter-buttons .btn:hover i {
            transform: scale(1.1);
        }

        .filter-buttons .btn-primary i {
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
        }

        .form-control {
            padding: 0.875rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(5px);
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
            background: white;
            transform: translateY(-1px);
        }

        .btn {
            padding: 0.875rem 1.75rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0F4C75 0%, #3282B8 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(15, 76, 117, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(15, 76, 117, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.8);
            color: #374151;
            border: 2px solid #e5e7eb;
            backdrop-filter: blur(5px);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 1);
            border-color: #0F4C75;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(15, 76, 117, 0.2);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        }

        /* Enhanced Statistics Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .stat-card-title {
            font-size: 0.8rem;
            text-transform: uppercase;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 0.75rem;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .stat-card-value {
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, #1f2937 0%, #667eea 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
        }

        /* Enhanced Data Table */
        .table-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .table-card:hover {
            box-shadow: 0 25px 50px rgba(0,0,0,0.12);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            width: 100%;
        }

        .table-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
            min-width: 0;
        }

        .table-actions {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            justify-content: flex-end;
            flex-shrink: 0;
        }

        /* Enhanced DataTable Styling */
        .dataTables_wrapper {
            font-size: 0.875rem;
        }

        .dataTables_length select,
        .dataTables_filter input {
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.875rem;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(5px);
            transition: all 0.3s ease;
        }

        .dataTables_filter input:focus,
        .dataTables_length select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
            background: white;
            transform: translateY(-1px);
        }

        .dataTables_info {
            color: #6b7280;
            font-weight: 500;
        }

        /* Enhanced Pagination Styling */
        .dataTables_wrapper .dataTables_paginate {
            margin-top: 2rem;
            text-align: center;
        }

        .dataTables_paginate {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
            padding: 1rem 0;
        }

        .dataTables_paginate .paginate_button {
            padding: 0.75rem 1.25rem;
            margin: 0;
            border-radius: 12px;
            border: 2px solid transparent;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            color: #374151;
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            min-width: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .dataTables_paginate .paginate_button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .dataTables_paginate .paginate_button:hover::before {
            left: 100%;
        }

        .dataTables_paginate .paginate_button:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateY(-2px) scale(1.05);
            border-color: rgba(102, 126, 234, 0.3);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .dataTables_paginate .paginate_button.current,
        .dataTables_paginate .paginate_button.current:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: rgba(102, 126, 234, 0.5);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            transform: translateY(-1px);
        }

        .dataTables_paginate .paginate_button.disabled,
        .dataTables_paginate .paginate_button.disabled:hover {
            background: rgba(229, 231, 235, 0.5);
            color: #9ca3af;
            transform: none;
            box-shadow: none;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .dataTables_paginate .paginate_button.previous,
        .dataTables_paginate .paginate_button.next {
            font-weight: 700;
            padding: 0.75rem 1.5rem;
            background: rgba(255, 255, 255, 0.95);
        }

        .dataTables_paginate .paginate_button.previous:hover,
        .dataTables_paginate .paginate_button.next:hover {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        }

        .dataTables_paginate .paginate_button.first,
        .dataTables_paginate .paginate_button.last {
            background: rgba(249, 250, 251, 0.9);
            border: 2px solid #e5e7eb;
        }

        .dataTables_paginate .paginate_button.first:hover,
        .dataTables_paginate .paginate_button.last:hover {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            border-color: rgba(245, 158, 11, 0.3);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
        }

        /* Pagination info styling */
        .dataTables_wrapper .dataTables_info {
            color: #6b7280;
            font-weight: 500;
            font-size: 0.875rem;
            padding: 1rem 0 0.5rem 0;
            text-align: center;
        }

        table.dataTable {
            border-collapse: collapse;
            width: 100%;
        }

        table.dataTable thead th {
            background: linear-gradient(135deg, #0F4C75 0%, #3282B8 100%);
            padding: 1.25rem;
            font-weight: 700;
            color: white;
            border-bottom: 2px solid #0F4C75;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }

        table.dataTable thead th::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #BBE1FA, #ffffff, #BBE1FA);
        }

        table.dataTable tbody td {
            padding: 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
            transition: all 0.2s ease;
        }

        table.dataTable tbody tr {
            transition: all 0.3s ease;
        }

        table.dataTable tbody tr:hover {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            transform: scale(1.01);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        /* Enhanced Status Badges */
        .status-badge {
            padding: 0.375rem 1rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.2s ease;
        }

        .status-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .status-badge.success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            border: 1px solid #86efac;
        }

        .status-badge.success::before {
            content: '✓';
            font-weight: 900;
        }

        .status-badge.failure {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            border: 1px solid #f87171;
        }

        .status-badge.failure::before {
            content: '✗';
            font-weight: 900;
        }

        .status-badge.pending {
            background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
            color: #92400e;
            border: 1px solid #fbbf24;
        }

        .status-badge.pending::before {
            content: '⏱';
            font-weight: 900;
        }

        .status-badge.in-progress {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1e40af;
            border: 1px solid #60a5fa;
        }

        .status-badge.in-progress::before {
            content: '⚡';
            font-weight: 900;
        }

        /* Enhanced Event Type Badges */
        .event-badge {
            padding: 0.5rem 1rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: #374151;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .event-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .event-badge.registration { 
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); 
            color: #1e40af; 
            border-color: #60a5fa;
        }
        .event-badge.registration::before { content: '📝'; }
        
        .event-badge.curp { 
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); 
            color: #065f46; 
            border-color: #86efac;
        }
        .event-badge.curp::before { content: '🆔'; }
        
        .event-badge.face { 
            background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 100%); 
            color: #be185d; 
            border-color: #f472b6;
        }
        .event-badge.face::before { content: '👤'; }
        
        .event-badge.admin { 
            background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%); 
            color: #7c3aed; 
            border-color: #a855f7;
        }
        .event-badge.admin::before { content: '🔐'; }
        
        .event-badge.login { 
            background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%); 
            color: #92400e; 
            border-color: #fbbf24;
        }
        .event-badge.login::before { content: '🔑'; }

        /* Enhanced Loading States */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: all 0.3s ease;
        }

        .loading-overlay.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .loading-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 2.5rem;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(102, 126, 234, 0.2);
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1.5rem;
        }

        .loading-content p {
            color: #374151;
            font-weight: 600;
            margin: 0;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Smooth animations */
        * {
            scroll-behavior: smooth;
        }

        /* Glass morphism effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            transition: all 0.3s ease;
            padding: 20px;
        }

        .modal-overlay.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .modal-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            border: 1px solid rgba(255, 255, 255, 0.3);
            max-width: 800px;
            width: 100%;
            max-height: 90vh;
            overflow: hidden;
            position: relative;
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }

        .modal-overlay:not(.hidden) .modal-content {
            transform: scale(1);
        }

        .modal-header {
            background: linear-gradient(135deg, #0F4C75 0%, #3282B8 100%);
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .modal-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="modal-grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="0.5" fill="%23ffffff" opacity="0.03"/></pattern></defs><rect width="100" height="100" fill="url(%23modal-grain)"/></svg>');
            pointer-events: none;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 0;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .modal-close {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 1;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 2rem;
            max-height: 70vh;
            overflow-y: auto;
        }

        .detail-grid {
            display: grid;
            gap: 1.5rem;
        }

        .detail-section {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid rgba(15, 76, 117, 0.1);
        }

        .detail-section-title {
            font-size: 1rem;
            font-weight: 700;
            color: #0F4C75;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .detail-row {
            display: flex;
            margin-bottom: 0.75rem;
            align-items: flex-start;
        }

        .detail-label {
            font-weight: 600;
            color: #374151;
            min-width: 120px;
            margin-right: 1rem;
        }

        .detail-value {
            color: #4b5563;
            flex: 1;
            word-break: break-word;
        }

        .json-code {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
            max-height: 200px;
            overflow-y: auto;
            white-space: pre-wrap;
        }

        /* Enhanced interactive elements */
        .interactive-element {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .interactive-element:hover {
            transform: translateY(-2px);
        }

        /* Floating animation for cards */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .stat-card:nth-child(odd) {
            animation: float 6s ease-in-out infinite;
            animation-delay: 0s;
        }

        .stat-card:nth-child(even) {
            animation: float 6s ease-in-out infinite;
            animation-delay: 3s;
        }

        /* Pulse effect for active elements */
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(102, 126, 234, 0); }
            100% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0); }
        }

        .btn-primary:active {
            animation: pulse 0.5s;
        }

        /* Enhanced Responsive Design */
        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            }

            .filters-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .admin-container {
                padding: 1rem;
            }

            .page-header {
                padding: 1.5rem;
                border-radius: 16px;
            }

            .page-title {
                font-size: 1.75rem;
                flex-direction: column;
                text-align: center;
                gap: 0.5rem;
            }

            .filters-card, .table-card {
                padding: 1.5rem;
                border-radius: 16px;
            }

            .filters-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }


            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .stat-card {
                padding: 1.5rem;
            }

            .table-card {
                padding: 1rem;
                overflow-x: auto;
            }

            .admin-header {
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
                gap: 0.5rem;
            }

            .admin-nav a {
                font-size: 0.875rem;
                padding: 0.5rem 1rem;
            }

            .table-actions {
                flex-direction: column;
                gap: 0.5rem;
                width: 100%;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            /* Enhanced Filter buttons responsive styling */
            .filter-buttons {
                gap: 1rem;
                justify-content: center;
                margin-top: 1rem;
            }

            .filter-buttons .btn {
                flex: 1;
                min-width: 140px;
                max-width: 200px;
                padding: 0.875rem 1.5rem;
                font-size: 0.85rem;
            }

            #auditLogsTable {
                font-size: 0.75rem;
            }

            table.dataTable thead th,
            table.dataTable tbody td {
                padding: 0.75rem 0.5rem;
            }

            .status-badge, .event-badge {
                font-size: 0.625rem;
                padding: 0.25rem 0.5rem;
            }

            /* Enhanced Responsive Pagination for Mobile */
            .dataTables_paginate {
                gap: 0.25rem;
                padding: 0.75rem 0;
                justify-content: center;
            }

            .dataTables_paginate .paginate_button {
                padding: 0.625rem 0.875rem;
                font-size: 0.75rem;
                min-width: 36px;
                border-radius: 10px;
            }

            .dataTables_paginate .paginate_button.previous,
            .dataTables_paginate .paginate_button.next {
                padding: 0.625rem 1rem;
            }

            /* Hide first/last buttons on very small screens */
            .dataTables_paginate .paginate_button.first,
            .dataTables_paginate .paginate_button.last {
                display: none;
            }

            .dataTables_wrapper .dataTables_info {
                font-size: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            .admin-container {
                padding: 0.75rem;
            }

            .page-header {
                padding: 1rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .filters-card, .table-card {
                padding: 1rem;
                border-radius: 12px;
            }

            .stat-card {
                padding: 1rem;
            }

            .stat-card-value {
                font-size: 1.5rem;
            }

            .form-control {
                padding: 0.75rem;
            }

            .btn {
                padding: 0.75rem 1.25rem;
                font-size: 0.8rem;
            }


            /* Ultra-small screen pagination */
            .dataTables_paginate {
                gap: 0.125rem;
                padding: 0.5rem 0;
            }

            .dataTables_paginate .paginate_button {
                padding: 0.5rem 0.75rem;
                font-size: 0.7rem;
                min-width: 32px;
                border-radius: 8px;
            }

            .dataTables_paginate .paginate_button.previous,
            .dataTables_paginate .paginate_button.next {
                padding: 0.5rem 0.875rem;
            }

            /* Show only essential pagination elements on ultra-small screens */
            .dataTables_paginate .paginate_button:not(.previous):not(.next):not(.current) {
                display: none;
            }

            .dataTables_paginate .paginate_button.current ~ .paginate_button:not(.next),
            .dataTables_paginate .paginate_button.current ~ .paginate_button:not(.next) ~ .paginate_button:not(.next) {
                display: inline-flex;
            }

            .dataTables_wrapper .dataTables_info {
                font-size: 0.7rem;
                padding: 0.5rem 0;
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
    <div class="main-container">
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
                
                <div class="form-group">
                    <label class="form-label" style="visibility: hidden;">Aplicar</label>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                        Aplicar Filtros
                    </button>
                </div>
                
                <div class="form-group">
                    <label class="form-label" style="visibility: hidden;">Limpiar</label>
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
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay hidden" id="loadingOverlay">
        <div class="loading-content">
            <div class="spinner"></div>
            <p>Cargando datos de auditoría...</p>
        </div>
    </div>

    <!-- Audit Log Details Modal -->
    <div class="modal-overlay hidden" id="logDetailsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-eye"></i>
                    Detalles del Registro de Auditoría
                </h3>
                <button class="modal-close" onclick="closeLogDetails()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        // Global variables
        let auditTable = null;
        let filtersCollapsed = false;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Check authentication before initializing DataTable
            checkAuthentication().then(isAuthenticated => {
                if (isAuthenticated) {
                    initializeDataTable();
                } else {
                    alert('No tiene permisos de administrador. Redirigiendo al login...');
                    window.location.href = '/login';
                }
            });
        });
        
        // Function to check authentication
        async function checkAuthentication() {
            try {
                const response = await fetch('/admin/api/dashboard-stats');
                if (response.status === 401) {
                    return false;
                }
                const data = await response.json();
                if (data.error === 'Authentication required') {
                    return false;
                }
                return true;
            } catch (error) {
                console.error('Error checking authentication:', error);
                return false;
            }
        }

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
                    },
                    error: function(xhr, error, thrown) {
                        console.error('DataTables Ajax Error:', error, thrown);
                        console.error('Response:', xhr.responseText);
                        console.error('Status:', xhr.status);
                        
                        // Try to parse the response to check for authentication errors
                        let responseData = null;
                        try {
                            responseData = JSON.parse(xhr.responseText);
                        } catch (e) {
                            // Response is not JSON
                        }
                        
                        if (xhr.status === 401 || 
                            (responseData && responseData.error === 'Authentication required') ||
                            (responseData && responseData.message && responseData.message.includes('authentication'))) {
                            // Authentication error - redirect to login
                            alert('No tiene permisos de administrador o su sesión ha expirado. Redirigiendo al login...');
                            window.location.href = '/login';
                        } else {
                            // Other error - show user friendly message
                            alert('Error al cargar los datos de auditoría. Verifique su conexión e intente nuevamente.');
                        }
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

        async function viewLogDetails(logId) {
            try {
                // Show loading state
                document.getElementById('modalBody').innerHTML = '<div style="text-align: center; padding: 2rem;"><div class="spinner"></div><p>Cargando detalles...</p></div>';
                document.getElementById('logDetailsModal').classList.remove('hidden');

                // Fetch detailed log data
                const response = await fetch(`/admin/api/audit-log/${logId}`);
                
                if (!response.ok) {
                    throw new Error('Error loading log details');
                }

                const logData = await response.json();
                
                // Display the detailed information
                displayLogDetails(logData);
                
            } catch (error) {
                console.error('Error loading log details:', error);
                document.getElementById('modalBody').innerHTML = `
                    <div style="text-align: center; padding: 2rem; color: #ef4444;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                        <p>Error cargando los detalles del registro</p>
                        <p style="font-size: 0.875rem; opacity: 0.7;">${error.message}</p>
                    </div>
                `;
            }
        }

        function displayLogDetails(logData) {
            const eventDataJson = logData.event_data ? JSON.stringify(logData.event_data, null, 2) : 'No hay datos adicionales';
            
            document.getElementById('modalBody').innerHTML = `
                <div class="detail-grid">
                    <!-- Basic Information -->
                    <div class="detail-section">
                        <div class="detail-section-title">
                            <i class="fas fa-info-circle"></i>
                            Información Básica
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">ID:</div>
                            <div class="detail-value">${logData.id}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Tipo de Evento:</div>
                            <div class="detail-value"><span class="event-badge ${logData.event_type}">${logData.event_type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</span></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Estado:</div>
                            <div class="detail-value"><span class="status-badge ${logData.status}">${logData.status}</span></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Usuario ID:</div>
                            <div class="detail-value">${logData.user_id || 'N/A'}</div>
                        </div>
                    </div>

                    <!-- Technical Details -->
                    <div class="detail-section">
                        <div class="detail-section-title">
                            <i class="fas fa-server"></i>
                            Detalles Técnicos
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">IP Address:</div>
                            <div class="detail-value">${logData.ip_address}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">User Agent:</div>
                            <div class="detail-value">${logData.user_agent || 'N/A'}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Session ID:</div>
                            <div class="detail-value">${logData.session_id || 'N/A'}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Request Method:</div>
                            <div class="detail-value">${logData.request_method || 'N/A'}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Request URL:</div>
                            <div class="detail-value" style="word-break: break-all;">${logData.request_url || 'N/A'}</div>
                        </div>
                    </div>

                    <!-- Verification Details (if applicable) -->
                    ${logData.verification_id || logData.confidence_score ? `
                    <div class="detail-section">
                        <div class="detail-section-title">
                            <i class="fas fa-shield-alt"></i>
                            Detalles de Verificación
                        </div>
                        ${logData.verification_id ? `
                        <div class="detail-row">
                            <div class="detail-label">Verification ID:</div>
                            <div class="detail-value">${logData.verification_id}</div>
                        </div>
                        ` : ''}
                        ${logData.confidence_score ? `
                        <div class="detail-row">
                            <div class="detail-label">Confidence Score:</div>
                            <div class="detail-value">${logData.confidence_score}%</div>
                        </div>
                        ` : ''}
                    </div>
                    ` : ''}

                    <!-- Timestamps -->
                    <div class="detail-section">
                        <div class="detail-section-title">
                            <i class="fas fa-clock"></i>
                            Información Temporal
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Creado:</div>
                            <div class="detail-value">${new Date(logData.created_at).toLocaleString('es-ES', { 
                                year: 'numeric', 
                                month: 'long', 
                                day: 'numeric', 
                                hour: '2-digit', 
                                minute: '2-digit', 
                                second: '2-digit' 
                            })}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Actualizado:</div>
                            <div class="detail-value">${new Date(logData.updated_at).toLocaleString('es-ES', { 
                                year: 'numeric', 
                                month: 'long', 
                                day: 'numeric', 
                                hour: '2-digit', 
                                minute: '2-digit', 
                                second: '2-digit' 
                            })}</div>
                        </div>
                    </div>

                    <!-- Event Data -->
                    <div class="detail-section">
                        <div class="detail-section-title">
                            <i class="fas fa-code"></i>
                            Datos del Evento
                        </div>
                        <div class="json-code">${eventDataJson}</div>
                    </div>
                </div>
            `;
        }

        function closeLogDetails() {
            document.getElementById('logDetailsModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('logDetailsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLogDetails();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLogDetails();
            }
        });

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