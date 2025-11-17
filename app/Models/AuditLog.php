<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class AuditLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_type',
        'user_id',
        'status',
        'ip_address',
        'user_agent',
        'event_data',
        'session_id',
        'request_method',
        'request_url',
        'error_message',
        'verification_id',
        'confidence_score',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'event_data' => 'json', // MySQL native JSON support
        'confidence_score' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mysql';

    /**
     * Event type constants
     */
    const EVENT_REGISTRATION_STARTED = 'registration_started';
    const EVENT_CURP_VERIFICATION_ATTEMPT = 'curp_verification_attempt';
    const EVENT_CURP_VERIFICATION_SUCCESS = 'curp_verification_success';
    const EVENT_CURP_VERIFICATION_FAILURE = 'curp_verification_failure';
    const EVENT_INE_VERIFICATION_SUCCESS = 'ine_verification_success';
    const EVENT_INE_VERIFICATION_FAILURE = 'ine_verification_failure';
    const EVENT_FACE_MATCHING_ATTEMPT = 'face_matching_attempt';
    const EVENT_FACE_MATCHING_SUCCESS = 'face_matching_success';
    const EVENT_FACE_MATCHING_FAILURE = 'face_matching_failure';
    const EVENT_ACCOUNT_CREATION_COMPLETED = 'account_creation_completed';
    const EVENT_ACCOUNT_CREATED = 'account_created';
    const EVENT_ACCOUNT_CREATION_FAILURE = 'account_creation_failure';
    const EVENT_LOGIN_ATTEMPT = 'login_attempt';
    const EVENT_LOGIN_SUCCESS = 'login_success';
    const EVENT_LOGIN_FAILURE = 'login_failure';
    const EVENT_PASSWORD_RESET_REQUEST = 'password_reset_request';
    const EVENT_ADMIN_ACCESS = 'admin_access';
    const EVENT_ADMIN_LOGOUT = 'admin_logout';

    /**
     * Status constants
     */
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILURE = 'failure';
    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Log an audit event
     *
     * @param string $eventType
     * @param string $status
     * @param array $eventData
     * @param string|null $userId
     * @param string|null $verificationId
     * @param float|null $confidenceScore
     * @return AuditLog
     */
    public static function logEvent(
        string $eventType,
        string $status,
        array $eventData = [],
        string $userId = null,
        string $verificationId = null,
        float $confidenceScore = null
    ): ?AuditLog {
        try {
            return self::create([
                'event_type' => $eventType,
                'user_id' => $userId,
                'status' => $status,
                'ip_address' => request()->ip() ?? 'unknown',
                'user_agent' => request()->userAgent(),
                'event_data' => $eventData,
                'session_id' => session()->getId() ?? 'no-session',
                'request_method' => request()->method(),
                'request_url' => request()->fullUrl(),
                'verification_id' => $verificationId,
                'confidence_score' => $confidenceScore,
            ]);
        } catch (\Exception $e) {
            // Log to Laravel logs if database is unavailable
            \Log::warning('Failed to create audit log: ' . $e->getMessage(), [
                'event_type' => $eventType,
                'status' => $status,
                'user_id' => $userId
            ]);
            return null;
        }
    }

    /**
     * Log a CURP verification attempt
     */
    public static function logCurpVerification(string $curp, string $status, array $data = [], string $verificationId = null): ?AuditLog
    {
        return self::logEvent(
            $status === self::STATUS_SUCCESS ? self::EVENT_CURP_VERIFICATION_SUCCESS : self::EVENT_CURP_VERIFICATION_FAILURE,
            $status,
            array_merge($data, ['curp' => substr($curp, 0, 4) . '***' . substr($curp, -4)]), // Partially mask CURP
            $curp,
            $verificationId
        );
    }

    /**
     * Log a face verification attempt
     */
    public static function logFaceVerification(string $status, string $userId = null, float $confidence = null, string $verificationId = null): AuditLog
    {
        return self::logEvent(
            $status === self::STATUS_SUCCESS ? self::EVENT_FACE_MATCHING_SUCCESS : self::EVENT_FACE_MATCHING_FAILURE,
            $status,
            ['confidence_score' => $confidence],
            $userId,
            $verificationId,
            $confidence
        );
    }

    /**
     * Log registration started
     */
    public static function logRegistrationStarted(string $method = 'traditional', array $data = []): AuditLog
    {
        return self::logEvent(
            self::EVENT_REGISTRATION_STARTED,
            self::STATUS_IN_PROGRESS,
            array_merge($data, ['registration_method' => $method])
        );
    }

    /**
     * Log account creation completed
     */
    public static function logAccountCreated(string $userId, array $data = []): ?AuditLog
    {
        return self::logEvent(
            self::EVENT_ACCOUNT_CREATION_COMPLETED,
            self::STATUS_SUCCESS,
            $data,
            $userId
        );
    }

    /**
     * Get events for a specific user
     *
     * @param string $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getEventsByUser(string $userId)
    {
        return self::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get recent events
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getRecentEvents(int $limit = 100)
    {
        return self::orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get events by type
     *
     * @param string $eventType
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getEventsByType(string $eventType, int $limit = 100)
    {
        return self::where('event_type', $eventType)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get events statistics
     *
     * @return array
     */
    public static function getStatistics(): array
    {
        try {
            $totalEvents = self::count();
            $todayEvents = self::whereDate('created_at', today())->count();
            $successEvents = self::where('status', self::STATUS_SUCCESS)->count();
            $successRate = $totalEvents > 0 ? round(($successEvents / $totalEvents) * 100, 1) : 0;
            
            return [
                'total_events' => $totalEvents,
                'today_events' => $todayEvents,
                'success_rate' => $successRate,
                'top_events' => [
                    'curp_verification_success' => self::where('event_type', self::EVENT_CURP_VERIFICATION_SUCCESS)->count(),
                    'registration_started' => self::where('event_type', self::EVENT_REGISTRATION_STARTED)->count(),
                    'face_matching_success' => self::where('event_type', self::EVENT_FACE_MATCHING_SUCCESS)->count(),
                    'account_creation_completed' => self::where('event_type', self::EVENT_ACCOUNT_CREATION_COMPLETED)->count()
                ],
                'recent_failures' => self::where('status', self::STATUS_FAILURE)->whereDate('created_at', today())->count(),
                'unique_users' => self::distinct('user_id')->count('user_id')
            ];
        } catch (\Exception $e) {
            // Return empty statistics if database is unavailable
            return [
                'total_events' => 0,
                'today_events' => 0,
                'success_rate' => 0,
                'top_events' => [
                    'curp_verification_success' => 0,
                    'registration_started' => 0,
                    'face_matching_success' => 0,
                    'account_creation_completed' => 0
                ],
                'recent_failures' => 0,
                'unique_users' => 0
            ];
        }
    }

    /**
     * Create test audit logs for demonstration (ONLY USE FOR TESTING)
     */
    public static function createTestData()
    {
        try {
            $testData = [
                // Registration started events
                [
                    'event_type' => self::EVENT_REGISTRATION_STARTED,
                    'user_id' => 'session_abc123',
                    'status' => self::STATUS_IN_PROGRESS,
                    'ip_address' => '192.168.1.100',
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'event_data' => json_encode(['registration_method' => 'curp']),
                    'session_id' => 'sess_abc123',
                    'request_method' => 'POST',
                    'request_url' => '/registro',
                    'verification_id' => 'ver_abc123',
                ],
                
                // CURP verification success
                [
                    'event_type' => self::EVENT_CURP_VERIFICATION_SUCCESS,
                    'user_id' => 'RICJ830716HTSSNN05',
                    'status' => self::STATUS_SUCCESS,
                    'ip_address' => '192.168.1.100',
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'event_data' => json_encode(['verification_method' => 'verificamex_api', 'curp' => 'RICJ***NN05']),
                    'session_id' => 'sess_abc123',
                    'request_method' => 'POST',
                    'request_url' => '/curp/validate',
                    'verification_id' => 'ver_curp123',
                ],
                
                // Face matching success
                [
                    'event_type' => self::EVENT_FACE_MATCHING_SUCCESS,
                    'user_id' => 'RICJ830716HTSSNN05',
                    'status' => self::STATUS_SUCCESS,
                    'ip_address' => '192.168.1.100',
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'event_data' => json_encode(['confidence' => 95.7, 'has_selfie' => true, 'has_ine' => true]),
                    'session_id' => 'sess_abc123',
                    'request_method' => 'POST',
                    'request_url' => '/face-verification/compare',
                    'verification_id' => 'ver_face123',
                    'confidence_score' => 95.7,
                ],
                
                // Account creation completed
                [
                    'event_type' => self::EVENT_ACCOUNT_CREATION_COMPLETED,
                    'user_id' => 'RICJ830716HTSSNN05',
                    'status' => self::STATUS_SUCCESS,
                    'ip_address' => '192.168.1.100',
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'event_data' => json_encode(['registration_method' => 'curp']),
                    'session_id' => 'sess_abc123',
                    'request_method' => 'POST',
                    'request_url' => '/registro',
                    'verification_id' => 'ver_account123',
                ],
                
                // Admin access
                [
                    'event_type' => self::EVENT_ADMIN_ACCESS,
                    'user_id' => 'admin@marina.gob.mx',
                    'status' => self::STATUS_SUCCESS,
                    'ip_address' => '192.168.1.101',
                    'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
                    'event_data' => json_encode(['accessed_page' => 'dashboard']),
                    'session_id' => 'sess_admin123',
                    'request_method' => 'GET',
                    'request_url' => '/admin/dashboard',
                ],
                
                // Some failed events for testing
                [
                    'event_type' => self::EVENT_CURP_VERIFICATION_FAILURE,
                    'user_id' => 'INVALID123456789012',
                    'status' => self::STATUS_FAILURE,
                    'ip_address' => '192.168.1.102',
                    'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X)',
                    'event_data' => json_encode(['error' => 'Invalid CURP format', 'step' => 'format_validation']),
                    'session_id' => 'sess_fail123',
                    'request_method' => 'POST',
                    'request_url' => '/curp/validate',
                    'verification_id' => 'ver_fail123',
                    'error_message' => 'Invalid CURP format',
                ],
                
                [
                    'event_type' => self::EVENT_FACE_MATCHING_FAILURE,
                    'user_id' => 'MAGR920315HMCRNS02',
                    'status' => self::STATUS_FAILURE,
                    'ip_address' => '192.168.1.103',
                    'user_agent' => 'Mozilla/5.0 (Android 11; Mobile)',
                    'event_data' => json_encode(['confidence' => 65.2, 'error_reason' => 'Low confidence score']),
                    'session_id' => 'sess_fail456',
                    'request_method' => 'POST',
                    'request_url' => '/face-verification/compare',
                    'verification_id' => 'ver_faceFaile456',
                    'confidence_score' => 65.2,
                    'error_message' => 'Face matching confidence too low',
                ]
            ];
            
            foreach ($testData as $logData) {
                self::create($logData);
            }
            
            \Log::info('Test audit log data created successfully');
            return true;
            
        } catch (\Exception $e) {
            \Log::error('Failed to create test audit log data: ' . $e->getMessage());
            return false;
        }
    }
}
