<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Log admin access
        try {
            AuditLog::logEvent(
                AuditLog::EVENT_ADMIN_ACCESS,
                AuditLog::STATUS_SUCCESS,
                ['accessed_page' => 'dashboard']
            );
        } catch (\Exception $e) {
            Log::warning('Failed to log admin access: ' . $e->getMessage());
        }

        return view('admin.dashboard');
    }

    /**
     * Display audit logs with filtering and pagination
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function auditLogs(Request $request)
    {
        // Get filter parameters
        $eventType = $request->get('event_type');
        $status = $request->get('status');
        $userId = $request->get('user_id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $search = $request->get('search');
        
        // Since we can't run actual database queries, return simulated data
        $auditLogs = $this->getSimulatedAuditLogs($eventType, $status, $userId, $dateFrom, $dateTo, $search);
        
        // Get statistics
        $statistics = AuditLog::getStatistics();
        
        return view('admin.audit-logs', compact('auditLogs', 'statistics', 'eventType', 'status', 'userId', 'dateFrom', 'dateTo', 'search'));
    }

    /**
     * Get audit logs data as JSON for DataTables
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getAuditLogsData(Request $request): JsonResponse
    {
        try {
            $draw = $request->get('draw');
            $start = $request->get('start', 0);
            $length = $request->get('length', 25);
            $search = $request->get('search')['value'] ?? '';
            
            // Get filter parameters
            $eventType = $request->get('event_type');
            $status = $request->get('status');
            $userId = $request->get('user_id');
            
            // Simulate audit logs data
            $auditLogs = $this->getSimulatedAuditLogs($eventType, $status, $userId, null, null, $search);
            
            // Paginate results
            $totalRecords = count($auditLogs);
            $filteredRecords = $totalRecords; // In real implementation, this would be filtered count
            $paginatedLogs = array_slice($auditLogs, $start, $length);
            
            return response()->json([
                'draw' => intval($draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $paginatedLogs
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching audit logs data: ' . $e->getMessage());
            
            return response()->json([
                'draw' => intval($request->get('draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Error fetching audit logs'
            ]);
        }
    }

    /**
     * Export audit logs to CSV
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportAuditLogs(Request $request)
    {
        $filename = 'audit_logs_' . date('Y-m-d_H-i-s') . '.csv';
        
        return response()->streamDownload(function() use ($request) {
            $handle = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($handle, [
                'ID',
                'Event Type',
                'User ID',
                'Status',
                'IP Address',
                'Timestamp',
                'User Agent',
                'Session ID',
                'Request Method',
                'Request URL',
                'Verification ID',
                'Confidence Score',
                'Event Data'
            ]);
            
            // Get simulated data
            $auditLogs = $this->getSimulatedAuditLogs();
            
            foreach ($auditLogs as $log) {
                fputcsv($handle, [
                    $log['id'],
                    $log['event_type'],
                    $log['user_id'],
                    $log['status'],
                    $log['ip_address'],
                    $log['created_at'],
                    $log['user_agent'],
                    $log['session_id'],
                    $log['request_method'],
                    $log['request_url'],
                    $log['verification_id'],
                    $log['confidence_score'],
                    json_encode($log['event_data'])
                ]);
            }
            
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Get dashboard statistics
     *
     * @return JsonResponse
     */
    public function getDashboardStats(): JsonResponse
    {
        try {
            $stats = [
                'total_registrations_today' => 23,
                'total_registrations_week' => 156,
                'total_registrations_month' => 687,
                'curp_verification_success_rate' => 94.2,
                'face_verification_success_rate' => 89.7,
                'total_failed_attempts_today' => 8,
                'recent_activities' => $this->getRecentActivities(),
                'hourly_registrations' => $this->getHourlyRegistrationData(),
                'verification_breakdown' => [
                    'curp_success' => 456,
                    'curp_failure' => 28,
                    'face_success' => 398,
                    'face_failure' => 45,
                    'account_completed' => 387
                ]
            ];
            
            return response()->json($stats);
            
        } catch (\Exception $e) {
            Log::error('Error fetching dashboard stats: ' . $e->getMessage());
            return response()->json(['error' => 'Error fetching statistics'], 500);
        }
    }

    /**
     * Get simulated audit logs data
     *
     * @param string|null $eventType
     * @param string|null $status
     * @param string|null $userId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $search
     * @return array
     */
    private function getSimulatedAuditLogs($eventType = null, $status = null, $userId = null, $dateFrom = null, $dateTo = null, $search = null): array
    {
        $logs = [];
        
        // Generate simulated audit log entries
        $events = [
            ['type' => 'registration_started', 'status' => 'in_progress', 'user' => 'RICJ830716HTSSNN05'],
            ['type' => 'curp_verification_success', 'status' => 'success', 'user' => 'RICJ830716HTSSNN05'],
            ['type' => 'face_matching_success', 'status' => 'success', 'user' => 'RICJ830716HTSSNN05'],
            ['type' => 'account_creation_completed', 'status' => 'success', 'user' => 'RICJ830716HTSSNN05'],
            ['type' => 'registration_started', 'status' => 'in_progress', 'user' => 'MAGR920315HMCRNS02'],
            ['type' => 'curp_verification_failure', 'status' => 'failure', 'user' => 'MAGR920315HMCRNS02'],
            ['type' => 'face_matching_failure', 'status' => 'failure', 'user' => 'PEDR850925MPLRZN05'],
            ['type' => 'login_attempt', 'status' => 'success', 'user' => 'RICJ830716HTSSNN05'],
            ['type' => 'admin_access', 'status' => 'success', 'user' => 'admin@marina.gob.mx'],
            ['type' => 'password_reset_request', 'status' => 'success', 'user' => 'LOPZ900101HDFPZR01'],
        ];
        
        for ($i = 0; $i < 50; $i++) {
            $event = $events[array_rand($events)];
            $timestamp = now()->subMinutes(rand(1, 1440))->format('Y-m-d H:i:s');
            
            $log = [
                'id' => $i + 1,
                'event_type' => $event['type'],
                'user_id' => $event['user'],
                'status' => $event['status'],
                'ip_address' => '192.168.' . rand(1, 255) . '.' . rand(1, 255),
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'event_data' => [
                    'registration_method' => rand(0, 1) ? 'traditional' : 'curp',
                    'confidence_score' => $event['type'] === 'face_matching_success' ? rand(80, 98) : null
                ],
                'session_id' => 'sess_' . uniqid(),
                'request_method' => rand(0, 1) ? 'GET' : 'POST',
                'request_url' => '/registro',
                'verification_id' => 'ver_' . uniqid(),
                'confidence_score' => $event['type'] === 'face_matching_success' ? rand(80, 98) : null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
            
            // Apply filters
            $includeLog = true;
            
            if ($eventType && $log['event_type'] !== $eventType) {
                $includeLog = false;
            }
            
            if ($status && $log['status'] !== $status) {
                $includeLog = false;
            }
            
            if ($userId && strpos($log['user_id'], $userId) === false) {
                $includeLog = false;
            }
            
            if ($search && stripos(json_encode($log), $search) === false) {
                $includeLog = false;
            }
            
            if ($includeLog) {
                $logs[] = $log;
            }
        }
        
        // Sort by timestamp (newest first)
        usort($logs, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        return $logs;
    }

    /**
     * Get recent activities for dashboard
     *
     * @return array
     */
    private function getRecentActivities(): array
    {
        return [
            [
                'type' => 'account_creation_completed',
                'user_id' => 'RICJ830716HTSSNN05',
                'status' => 'success',
                'timestamp' => now()->subMinutes(5)->format('H:i'),
                'message' => 'Nueva cuenta creada exitosamente'
            ],
            [
                'type' => 'face_matching_failure',
                'user_id' => 'MAGR920315HMCRNS02',
                'status' => 'failure',
                'timestamp' => now()->subMinutes(12)->format('H:i'),
                'message' => 'Verificación facial falló (confianza: 65%)'
            ],
            [
                'type' => 'curp_verification_success',
                'user_id' => 'PEDR850925MPLRZN05',
                'status' => 'success',
                'timestamp' => now()->subMinutes(18)->format('H:i'),
                'message' => 'CURP verificado correctamente'
            ],
            [
                'type' => 'admin_access',
                'user_id' => 'admin@marina.gob.mx',
                'status' => 'success',
                'timestamp' => now()->subMinutes(25)->format('H:i'),
                'message' => 'Acceso al panel de administración'
            ]
        ];
    }

    /**
     * Get hourly registration data for charts
     *
     * @return array
     */
    private function getHourlyRegistrationData(): array
    {
        $data = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $data[] = [
                'hour' => sprintf('%02d:00', $hour),
                'registrations' => rand(0, 15),
                'successful' => rand(0, 12),
                'failed' => rand(0, 3)
            ];
        }
        return $data;
    }
}
