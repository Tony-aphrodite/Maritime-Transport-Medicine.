<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuditLog;
use App\Services\FileDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class AuthApiController extends Controller
{
    private function isDatabaseAvailable()
    {
        try {
            \DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function authenticateWithFileDatabase($email, $password, FileDatabase $fileDb)
    {
        $user = $fileDb->findUserByEmail($email);
        if (!$user || !Hash::check($password, $user['password'])) {
            return null;
        }
        
        // Create a mock user object for session
        $mockUser = new \stdClass();
        $mockUser->id = $user['id'];
        $mockUser->email = $user['email'];
        $mockUser->name = $user['name'];
        $mockUser->email_verified_at = $user['email_verified_at'];
        
        return $mockUser;
    }

    /**
     * Handle login request via AJAX
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos invalidos',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Check if database is available
            if ($this->isDatabaseAvailable()) {
                // Use normal Laravel authentication
                $credentials = $request->only('email', 'password');

                if (Auth::attempt($credentials, $request->boolean('remember'))) {
                    $request->session()->regenerate();
                    $user = Auth::user();

                    // Log the successful login
                    try {
                        AuditLog::logEvent(
                            'login_success',
                            AuditLog::STATUS_SUCCESS,
                            ['user_id' => $user->id],
                            $user->email
                        );
                    } catch (\Exception $logException) {
                        // Silently ignore logging errors
                    }

                    // Check if email is verified
                    if (!$user->hasVerifiedEmail()) {
                        Auth::logout();
                        return response()->json([
                            'success' => false,
                            'message' => 'Por favor verifica tu correo electronico antes de iniciar sesion.',
                            'needs_verification' => true,
                            'email' => $user->email
                        ], 403);
                    }

                    // Check if profile is completed
                    $redirect = '/dashboard';
                    if (method_exists($user, 'hasCompletedProfile') && !$user->hasCompletedProfile()) {
                        $redirect = '/complete-profile';
                    }

                    return response()->json([
                        'success' => true,
                        'message' => '¡Inicio de sesion exitoso!',
                        'redirect' => $redirect,
                        'user' => [
                            'id' => $user->id,
                            'email' => $user->email,
                            'name' => $user->name,
                            'profile_completed' => method_exists($user, 'hasCompletedProfile') ? $user->hasCompletedProfile() : true
                        ]
                    ]);
                }
            } else {
                // Use file database fallback
                $fileDb = new FileDatabase();
                $user = $this->authenticateWithFileDatabase($request->email, $request->password, $fileDb);
                
                if ($user) {
                    // Store user in session manually
                    session(['auth_user' => $user]);
                    
                    $fileDb->logAudit('login_success', 'success', ['method' => 'file_db'], $user->email);

                    return response()->json([
                        'success' => true,
                        'message' => '¡Inicio de sesion exitoso! (Modo archivo)',
                        'redirect' => '/dashboard',
                        'user' => [
                            'id' => $user->id,
                            'email' => $user->email,
                            'name' => $user->name,
                            'profile_completed' => true
                        ],
                        'note' => 'Usando base de datos de archivos como respaldo'
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas. Verifica tu correo y contrasena.'
            ], 401);

        } catch (\Exception $e) {
            // Check if it's a database connection error
            if (str_contains($e->getMessage(), 'could not find driver') ||
                str_contains($e->getMessage(), 'Connection refused') ||
                str_contains($e->getMessage(), 'SQLSTATE')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de conexion a la base de datos. Por favor contacte al administrador.',
                    'debug_error' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error al iniciar sesion. Por favor intenta de nuevo.',
                'debug_error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Handle registration request via AJAX
     */
    public function register(Request $request)
    {
        // Basic validation first (without unique check for file database compatibility)
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.required' => 'El correo electronico es requerido',
            'email.email' => 'Ingresa un correo electronico valido',
            'password.required' => 'La contrasena es requerida',
            'password.min' => 'La contrasena debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contrasenas no coinciden',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validacion',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Check if database is available
            if ($this->isDatabaseAvailable()) {
                // Use normal Laravel registration
                $user = User::create([
                    'name' => explode('@', $request->email)[0], // Temporary name from email
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                // Trigger the Registered event (sends verification email)
                event(new Registered($user));

                // Log the registration
                try {
                    AuditLog::logEvent(
                        'registration_success',
                        AuditLog::STATUS_SUCCESS,
                        ['user_id' => $user->id],
                        $user->email
                    );
                } catch (\Exception $logException) {
                    // Silently ignore logging errors
                }

                return response()->json([
                    'success' => true,
                    'message' => '¡Registro exitoso! Por favor revisa tu correo electronico para verificar tu cuenta.',
                    'email' => $request->email,
                    'needs_verification' => true
                ]);
            } else {
                // Use file database fallback
                $fileDb = new FileDatabase();
                
                $userData = [
                    'name' => explode('@', $request->email)[0],
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ];
                
                $user = $fileDb->createUser($userData);
                $fileDb->logAudit('registration_success', 'success', ['method' => 'file_db'], $user['email']);

                return response()->json([
                    'success' => true,
                    'message' => '¡Registro exitoso! (Modo archivo - verificacion automatica)',
                    'email' => $request->email,
                    'needs_verification' => false,
                    'note' => 'Usando base de datos de archivos como respaldo'
                ]);
            }

        } catch (\Exception $e) {
            // Try to log the error
            try {
                AuditLog::logEvent(
                    'registration_failed',
                    AuditLog::STATUS_FAILURE,
                    ['error' => $e->getMessage()],
                    $request->email
                );
            } catch (\Exception $logException) {
                // Silently ignore logging errors
            }

            // Check if it's a database connection error
            if (str_contains($e->getMessage(), 'could not find driver') ||
                str_contains($e->getMessage(), 'Connection refused') ||
                str_contains($e->getMessage(), 'SQLSTATE')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de conexion a la base de datos. Por favor contacte al administrador.',
                    'debug_error' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error al registrar. Por favor intenta de nuevo.',
                'debug_error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Resend verification email
     */
    public function resendVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Correo electrónico inválido'
            ], 422);
        }

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró una cuenta con este correo'
                ], 404);
            }

            if ($user->hasVerifiedEmail()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este correo ya ha sido verificado'
                ], 400);
            }

            // Resend verification email
            $user->sendEmailVerificationNotification();

            try {
                AuditLog::logEvent(
                    'verification_email_resent',
                    AuditLog::STATUS_SUCCESS,
                    ['user_id' => $user->id],
                    $user->email
                );
            } catch (\Exception $logException) {
                // Silently ignore logging errors
            }

            return response()->json([
                'success' => true,
                'message' => '¡Correo de verificación reenviado!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de conexión a la base de datos. Por favor contacte al administrador.'
            ], 500);
        }
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            try {
                AuditLog::logEvent(
                    'logout',
                    AuditLog::STATUS_SUCCESS,
                    ['user_id' => $user->id],
                    $user->email
                );
            } catch (\Exception $e) {
                // Silently ignore logging errors
            }
        }

        // Clear file database session if exists
        session()->forget('auth_user');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Check if request expects JSON (AJAX) or redirect (form)
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Sesion cerrada exitosamente',
                'redirect' => '/'
            ]);
        }

        // For form submission, redirect to home
        return redirect('/')->with('success', 'Sesion cerrada exitosamente');
    }
}
