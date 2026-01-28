<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminAppointmentController extends Controller
{
    /**
     * Check if admin is logged in
     */
    private function checkAdminAuth()
    {
        if (!Session::get('admin_logged_in')) {
            return false;
        }
        return true;
    }

    /**
     * Display the appointments list
     */
    public function index(Request $request)
    {
        if (!$this->checkAdminAuth()) {
            return redirect('/')->with('error', 'Debe iniciar sesión como administrador');
        }

        $query = Appointment::with('user');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('appointment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('appointment_date', '<=', $request->date_to);
        }

        // Search by user name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                  ->orWhere('apellido_paterno', 'like', "%{$search}%")
                  ->orWhere('apellido_materno', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('curp', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $appointments = $query->paginate(15)->withQueryString();

        // Get statistics
        $stats = [
            'total' => Appointment::count(),
            'pending_payment' => Appointment::where('status', 'pending_payment')->count(),
            'confirmed' => Appointment::where('status', 'confirmed')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->count(),
            'today' => Appointment::whereDate('appointment_date', today())->count(),
        ];

        return view('admin.appointments.index', compact('appointments', 'stats'));
    }

    /**
     * Display appointment details
     */
    public function show($id)
    {
        if (!$this->checkAdminAuth()) {
            return redirect('/')->with('error', 'Debe iniciar sesión como administrador');
        }

        $appointment = Appointment::with(['user', 'documents'])->findOrFail($id);

        return view('admin.appointments.show', compact('appointment'));
    }

    /**
     * Update appointment status
     */
    public function updateStatus(Request $request, $id)
    {
        if (!$this->checkAdminAuth()) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 401);
        }

        $request->validate([
            'status' => 'required|in:pending_payment,confirmed,completed,cancelled',
        ]);

        $appointment = Appointment::findOrFail($id);
        $oldStatus = $appointment->status;
        $newStatus = $request->status;

        // Validate status transitions
        $validTransitions = [
            'pending_payment' => ['confirmed', 'cancelled'],
            'confirmed' => ['completed', 'cancelled'],
            'completed' => [],
            'cancelled' => [],
        ];

        if (!in_array($newStatus, $validTransitions[$oldStatus])) {
            return response()->json([
                'success' => false,
                'message' => "No se puede cambiar de '{$oldStatus}' a '{$newStatus}'"
            ], 422);
        }

        $appointment->status = $newStatus;

        // Set payment date if confirming
        if ($newStatus === 'confirmed' && $oldStatus === 'pending_payment') {
            $appointment->payment_status = 'paid';
            $appointment->payment_date = now();
        }

        $appointment->save();

        // Log the status change
        try {
            AuditLog::logEvent(
                'appointment_status_changed',
                AuditLog::STATUS_SUCCESS,
                [
                    'appointment_id' => $appointment->id,
                    'user_id' => $appointment->user_id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'admin_email' => Session::get('admin_email'),
                ],
                Session::get('admin_email')
            );
        } catch (\Exception $e) {
            // Silently ignore logging errors
        }

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado correctamente',
            'new_status' => $newStatus,
            'status_label' => $appointment->status_label,
        ]);
    }

    /**
     * Get appointments data for API (AJAX)
     */
    public function getAppointmentsData(Request $request)
    {
        if (!$this->checkAdminAuth()) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        $query = Appointment::with('user');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('appointment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('appointment_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                  ->orWhere('apellido_paterno', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // DataTables parameters
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);

        $total = $query->count();
        $appointments = $query->orderBy('created_at', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $appointments->map(function ($appointment) {
            return [
                'id' => $appointment->id,
                'user_name' => $appointment->user ?
                    $appointment->user->nombres . ' ' . $appointment->user->apellido_paterno : 'N/A',
                'user_email' => $appointment->user->email ?? 'N/A',
                'appointment_date' => $appointment->appointment_date->format('d/m/Y'),
                'appointment_time' => $appointment->appointment_time,
                'exam_type' => $appointment->exam_type_label,
                'status' => $appointment->status,
                'status_label' => $appointment->status_label,
                'total' => number_format($appointment->total, 2),
                'created_at' => $appointment->created_at->format('d/m/Y H:i'),
            ];
        });

        return response()->json([
            'draw' => $request->get('draw', 1),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data,
        ]);
    }

    /**
     * Get appointment statistics
     */
    public function getStats()
    {
        if (!$this->checkAdminAuth()) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        $stats = [
            'total' => Appointment::count(),
            'pending_payment' => Appointment::where('status', 'pending_payment')->count(),
            'confirmed' => Appointment::where('status', 'confirmed')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->count(),
            'today' => Appointment::whereDate('appointment_date', today())->count(),
            'this_week' => Appointment::whereBetween('appointment_date', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'this_month' => Appointment::whereMonth('appointment_date', now()->month)
                ->whereYear('appointment_date', now()->year)
                ->count(),
            'total_revenue' => Appointment::where('payment_status', 'paid')->sum('total'),
        ];

        return response()->json($stats);
    }
}
