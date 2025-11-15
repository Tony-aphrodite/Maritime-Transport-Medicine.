<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Admin credentials (in production, these should be stored in database)
     */
    private $adminCredentials = [
        'email' => 'AdminJuan@gmail.com',
        'password' => 'johnson@suceess!'
    ];

    /**
     * Show admin login form
     *
     * @return \Illuminate\View\View
     */
    public function showLogin()
    {
        // If already logged in, redirect to dashboard
        if (Session::get('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    /**
     * Process admin login
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        // Check admin credentials
        if ($email === $this->adminCredentials['email'] && $password === $this->adminCredentials['password']) {
            // Login successful
            Session::put('admin_logged_in', true);
            Session::put('admin_email', $email);

            // Log successful admin login
            try {
                AuditLog::logEvent(
                    AuditLog::EVENT_LOGIN_SUCCESS,
                    AuditLog::STATUS_SUCCESS,
                    [
                        'authentication_method' => 'admin_credentials',
                        'email' => $email
                    ],
                    $email
                );
            } catch (\Exception $e) {
                Log::warning('Failed to log admin login success: ' . $e->getMessage());
            }

            return redirect()->route('admin.dashboard')->with('success', 'Sesión iniciada correctamente');

        } else {
            // Login failed
            try {
                AuditLog::logEvent(
                    AuditLog::EVENT_LOGIN_FAILURE,
                    AuditLog::STATUS_FAILURE,
                    [
                        'authentication_method' => 'admin_credentials',
                        'email' => $email,
                        'reason' => 'invalid_credentials'
                    ],
                    $email
                );
            } catch (\Exception $e) {
                Log::warning('Failed to log admin login failure: ' . $e->getMessage());
            }

            return back()->with('error', 'Credenciales incorrectas. Verifique su email y contraseña.');
        }
    }

    /**
     * Admin logout
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        $adminEmail = Session::get('admin_email');

        // Log admin logout
        try {
            AuditLog::logEvent(
                AuditLog::EVENT_ADMIN_LOGOUT,
                AuditLog::STATUS_SUCCESS,
                ['logout_method' => 'manual'],
                $adminEmail
            );
        } catch (\Exception $e) {
            Log::warning('Failed to log admin logout: ' . $e->getMessage());
        }

        Session::forget('admin_logged_in');
        Session::forget('admin_email');

        return redirect()->route('admin.login')->with('success', 'Sesión cerrada correctamente');
    }

    /**
     * Check if admin is authenticated
     *
     * @return bool
     */
    private function isAdminAuthenticated()
    {
        return Session::get('admin_logged_in', false);
    }

    /**
     * Display the admin dashboard
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function dashboard()
    {
        // Check authentication
        if (!$this->isAdminAuthenticated()) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión para acceder al panel administrativo');
        }

        // Log admin access
        try {
            AuditLog::logEvent(
                AuditLog::EVENT_ADMIN_ACCESS,
                AuditLog::STATUS_SUCCESS,
                ['accessed_page' => 'dashboard'],
                Session::get('admin_email')
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
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function auditLogs(Request $request)
    {
        // Check authentication
        if (!$this->isAdminAuthenticated()) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión para acceder al panel administrativo');
        }

        // Get filter parameters
        $eventType = $request->get('event_type');
        $status = $request->get('status');
        $userId = $request->get('user_id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $search = $request->get('search');
        
        // Get statistics for the audit logs page
        $statistics = AuditLog::getStatistics();
        
        return view('admin.audit-logs', compact('eventType', 'status', 'userId', 'dateFrom', 'dateTo', 'search', 'statistics'));
    }

    /**
     * Get individual audit log details
     */
    public function getAuditLogDetails($id)
    {
        // Check authentication
        if (!$this->isAdminAuthenticated()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        try {
            $log = AuditLog::findOrFail($id);
            
            return response()->json($log);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Log not found',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get dashboard statistics (REAL DATA)
     *
     * @return JsonResponse
     */
    public function getDashboardStats(): JsonResponse
    {
        // Check authentication for API endpoints
        if (!$this->isAdminAuthenticated()) {
            return response()->json([
                'error' => 'Authentication required',
                'message' => 'Admin authentication required for this action'
            ], 401);
        }
        try {
            // Get real data from database
            $today = Carbon::today();
            $thisWeek = Carbon::now()->startOfWeek();
            $thisMonth = Carbon::now()->startOfMonth();

            $stats = [
                'total_registrations_today' => AuditLog::where('event_type', AuditLog::EVENT_REGISTRATION_STARTED)
                    ->whereDate('created_at', $today)->count(),
                'total_registrations_week' => AuditLog::where('event_type', AuditLog::EVENT_REGISTRATION_STARTED)
                    ->where('created_at', '>=', $thisWeek)->count(),
                'total_registrations_month' => AuditLog::where('event_type', AuditLog::EVENT_REGISTRATION_STARTED)
                    ->where('created_at', '>=', $thisMonth)->count(),
            ];

            // Calculate success rates
            $curpSuccess = AuditLog::where('event_type', AuditLog::EVENT_CURP_VERIFICATION_SUCCESS)->count();
            $curpTotal = AuditLog::whereIn('event_type', [
                AuditLog::EVENT_CURP_VERIFICATION_SUCCESS,
                AuditLog::EVENT_CURP_VERIFICATION_FAILURE
            ])->count();
            $stats['curp_verification_success_rate'] = $curpTotal > 0 ? round(($curpSuccess / $curpTotal) * 100, 1) : 0;

            $faceSuccess = AuditLog::where('event_type', AuditLog::EVENT_FACE_MATCHING_SUCCESS)->count();
            $faceTotal = AuditLog::whereIn('event_type', [
                AuditLog::EVENT_FACE_MATCHING_SUCCESS,
                AuditLog::EVENT_FACE_MATCHING_FAILURE
            ])->count();
            $stats['face_verification_success_rate'] = $faceTotal > 0 ? round(($faceSuccess / $faceTotal) * 100, 1) : 0;

            $stats['total_failed_attempts_today'] = AuditLog::where('status', AuditLog::STATUS_FAILURE)
                ->whereDate('created_at', $today)->count();

            $stats['recent_activities'] = $this->getRealRecentActivities();
            $stats['hourly_registrations'] = $this->getRealHourlyRegistrationData();
            $stats['verification_breakdown'] = [
                'curp_success' => AuditLog::where('event_type', AuditLog::EVENT_CURP_VERIFICATION_SUCCESS)->count(),
                'curp_failure' => AuditLog::where('event_type', AuditLog::EVENT_CURP_VERIFICATION_FAILURE)->count(),
                'face_success' => AuditLog::where('event_type', AuditLog::EVENT_FACE_MATCHING_SUCCESS)->count(),
                'face_failure' => AuditLog::where('event_type', AuditLog::EVENT_FACE_MATCHING_FAILURE)->count(),
                'account_completed' => AuditLog::where('event_type', AuditLog::EVENT_ACCOUNT_CREATION_COMPLETED)->count()
            ];

            return response()->json($stats);

        } catch (\Exception $e) {
            Log::warning('Database unavailable, using empty data: ' . $e->getMessage());
            
            // Return empty data if database is not available
            return response()->json([
                'total_registrations_today' => 0,
                'total_registrations_week' => 0,
                'total_registrations_month' => 0,
                'curp_verification_success_rate' => 0,
                'face_verification_success_rate' => 0,
                'total_failed_attempts_today' => 0,
                'recent_activities' => [],
                'hourly_registrations' => [],
                'verification_breakdown' => [
                    'curp_success' => 0,
                    'curp_failure' => 0,
                    'face_success' => 0,
                    'face_failure' => 0,
                    'account_completed' => 0
                ],
                'database_status' => 'unavailable',
                'message' => 'No real audit data available. Please ensure database connection is working.'
            ]);
        }
    }

    /**
     * Get audit logs data for DataTables (REAL DATA)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getAuditLogsData(Request $request): JsonResponse
    {
        // Check authentication for API endpoints
        if (!$this->isAdminAuthenticated()) {
            return response()->json([
                'error' => 'Authentication required',
                'message' => 'Admin authentication required for this action'
            ], 401);
        }
        try {
            $query = AuditLog::query();

            // Apply filters
            if ($request->filled('event_type')) {
                $query->where('event_type', $request->get('event_type'));
            }

            if ($request->filled('status')) {
                $query->where('status', $request->get('status'));
            }

            if ($request->filled('user_id')) {
                $query->where('user_id', 'like', '%' . $request->get('user_id') . '%');
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->get('date_from'));
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->get('date_to'));
            }

            // Handle DataTables search
            $search = $request->get('search')['value'] ?? '';
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('user_id', 'like', '%' . $search . '%')
                      ->orWhere('event_type', 'like', '%' . $search . '%')
                      ->orWhere('status', 'like', '%' . $search . '%')
                      ->orWhere('ip_address', 'like', '%' . $search . '%');
                });
            }

            $totalRecords = AuditLog::count();
            $filteredRecords = $query->count();

            // Apply pagination
            $start = intval($request->get('start', 0));
            $length = intval($request->get('length', 10));
            
            $data = $query->orderBy('created_at', 'desc')
                         ->skip($start)
                         ->take($length)
                         ->get()
                         ->map(function($log) {
                             return [
                                 'id' => $log->id,
                                 'event_type' => $log->event_type,
                                 'user_id' => $log->user_id,
                                 'status' => $log->status,
                                 'ip_address' => $log->ip_address,
                                 'user_agent' => $log->user_agent,
                                 'event_data' => $log->event_data,
                                 'session_id' => $log->session_id,
                                 'request_method' => $log->request_method,
                                 'request_url' => $log->request_url,
                                 'verification_id' => $log->verification_id,
                                 'confidence_score' => $log->confidence_score,
                                 'created_at' => $log->created_at->format('Y-m-d H:i:s'),
                                 'updated_at' => $log->updated_at->format('Y-m-d H:i:s')
                             ];
                         });

            return response()->json([
                'draw' => intval($request->get('draw', 0)),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data,
                'database_status' => 'connected'
            ]);

        } catch (\Exception $e) {
            Log::warning('Database unavailable for audit logs: ' . $e->getMessage());
            
            return response()->json([
                'draw' => intval($request->get('draw', 0)),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'database_status' => 'unavailable',
                'message' => 'No real audit data available. Please ensure database connection is working.'
            ]);
        }
    }

    /**
     * Export audit logs to CSV (REAL DATA)
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
                'ID', 'Event Type', 'User ID', 'Status', 'IP Address', 
                'User Agent', 'Event Data', 'Session ID', 'Request Method', 
                'Request URL', 'Verification ID', 'Confidence Score', 
                'Created At', 'Updated At'
            ]);

            try {
                // Get real audit logs
                $query = AuditLog::query();
                
                // Apply any filters from request
                if ($request->filled('event_type')) {
                    $query->where('event_type', $request->get('event_type'));
                }
                if ($request->filled('status')) {
                    $query->where('status', $request->get('status'));
                }
                if ($request->filled('user_id')) {
                    $query->where('user_id', 'like', '%' . $request->get('user_id') . '%');
                }
                if ($request->filled('date_from')) {
                    $query->whereDate('created_at', '>=', $request->get('date_from'));
                }
                if ($request->filled('date_to')) {
                    $query->whereDate('created_at', '<=', $request->get('date_to'));
                }

                // Export in chunks for large datasets
                $query->orderBy('created_at', 'desc')->chunk(100, function($logs) use ($handle) {
                    foreach ($logs as $log) {
                        fputcsv($handle, [
                            $log->id,
                            $log->event_type,
                            $log->user_id,
                            $log->status,
                            $log->ip_address,
                            $log->user_agent,
                            json_encode($log->event_data),
                            $log->session_id,
                            $log->request_method,
                            $log->request_url,
                            $log->verification_id,
                            $log->confidence_score,
                            $log->created_at,
                            $log->updated_at
                        ]);
                    }
                });

            } catch (\Exception $e) {
                // If database is unavailable, add a note
                fputcsv($handle, ['No data available', 'Database connection error', '', '', '', '', '', '', '', '', '', '', '', '']);
                Log::warning('Cannot export audit logs, database unavailable: ' . $e->getMessage());
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * Get real recent activities from database
     */
    private function getRealRecentActivities()
    {
        try {
            return AuditLog::orderBy('created_at', 'desc')
                ->take(10)
                ->get()
                ->map(function($log) {
                    $message = $this->getEventMessage($log->event_type, $log->status, $log->event_data);
                    
                    return [
                        'type' => $log->event_type,
                        'user_id' => $log->user_id,
                        'status' => $log->status,
                        'timestamp' => $log->created_at->format('H:i'),
                        'message' => $message
                    ];
                });
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get real hourly registration data from database
     */
    private function getRealHourlyRegistrationData()
    {
        try {
            $today = Carbon::today();
            $data = [];
            
            for ($hour = 0; $hour < 24; $hour++) {
                $hourStart = $today->copy()->addHours($hour);
                $hourEnd = $hourStart->copy()->addHour();
                
                $registrations = AuditLog::where('event_type', AuditLog::EVENT_REGISTRATION_STARTED)
                    ->whereBetween('created_at', [$hourStart, $hourEnd])
                    ->count();
                    
                $successful = AuditLog::where('status', AuditLog::STATUS_SUCCESS)
                    ->whereBetween('created_at', [$hourStart, $hourEnd])
                    ->count();
                    
                $failed = AuditLog::where('status', AuditLog::STATUS_FAILURE)
                    ->whereBetween('created_at', [$hourStart, $hourEnd])
                    ->count();
                
                $data[] = [
                    'hour' => sprintf('%02d:00', $hour),
                    'registrations' => $registrations,
                    'successful' => $successful,
                    'failed' => $failed
                ];
            }
            
            return $data;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Generate event message for display
     */
    private function getEventMessage($eventType, $status, $eventData)
    {
        $eventData = is_string($eventData) ? json_decode($eventData, true) : $eventData;
        
        switch ($eventType) {
            case AuditLog::EVENT_REGISTRATION_STARTED:
                return 'Usuario inició proceso de registro';
            case AuditLog::EVENT_CURP_VERIFICATION_SUCCESS:
                return 'CURP verificado correctamente';
            case AuditLog::EVENT_CURP_VERIFICATION_FAILURE:
                return 'Error en verificación de CURP';
            case AuditLog::EVENT_FACE_MATCHING_SUCCESS:
                $confidence = $eventData['confidence'] ?? 'N/A';
                return "Verificación facial exitosa (confianza: {$confidence}%)";
            case AuditLog::EVENT_FACE_MATCHING_FAILURE:
                $confidence = $eventData['confidence'] ?? 'N/A';
                return "Verificación facial falló (confianza: {$confidence}%)";
            case AuditLog::EVENT_ACCOUNT_CREATION_COMPLETED:
                return 'Nueva cuenta creada exitosamente';
            case AuditLog::EVENT_ADMIN_ACCESS:
                return 'Acceso al panel de administración';
            case AuditLog::EVENT_LOGIN_SUCCESS:
                return 'Inicio de sesión administrativo exitoso';
            case AuditLog::EVENT_LOGIN_FAILURE:
                return 'Intento de inicio de sesión fallido';
            case AuditLog::EVENT_ADMIN_LOGOUT:
                return 'Cierre de sesión administrativo';
            default:
                return ucfirst(str_replace('_', ' ', $eventType));
        }
    }
}