<?php

namespace App\Http\Controllers\API;

use App\Events\ReservationApproved;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use App\Services\ApprovalService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ApprovalController extends Controller
{
    protected $approvalService;

    public function __construct(ApprovalService $approvalService)
    {
        $this->approvalService = $approvalService;
    }


    /**
     * Display a listing of reservations for approval.
     * Accessible via permission 'view reservations' (admin only via route middleware).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Forbidden. Only admin role allowed.',
                ], 403);
            }

            // Query dengan eager loading
            $query = Reservation::with(['student', 'approver']);


            // Filters
            if ($request->filled('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
                $query->where('status', $request->status);
            }
            if ($request->filled('student_id')) {
                $query->where('student_id', $request->student_id);
            }
            if ($request->filled('search')) {
                $query->where('purpose', 'like', '%' . $request->search . '%');
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $reservations = $query->paginate($perPage);

            // Transform data
            $data = $reservations->map(function ($reservation) {
                return [
                    'id' => $reservation->id,
                    'student' => [
                        'id' => $reservation->student->id,
                        'name' => $reservation->student->name,
                        'email' => $reservation->student->email,
                        'nim' => $reservation->student->nim ?? null,
                    ],
                    'purpose' => $reservation->purpose,
                    'request_date' => $reservation->request_date,
                    'status' => $reservation->status,
                    'rejection_reason' => $reservation->rejection_reason,
                    'approval_letter' => $reservation->approval_letter ? asset('storage/' . $reservation->approval_letter) : null, // Asumsi storage link
                    'approved_by' => $reservation->approver ? [
                        'id' => $reservation->approver->id,
                        'name' => $reservation->approver->name,
                    ] : null,
                    'created_at' => $reservation->created_at,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => $reservations->isEmpty() ? 'No reservations found.' : 'List retrieved successfully.',
                'data' => $data,
                'pagination' => [
                    'current_page' => $reservations->currentPage(),
                    'total_pages' => $reservations->lastPage(),
                    'total_items' => $reservations->total(),
                    'per_page' => $reservations->perPage(),
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    // use ApprovalService


    /**
     * Approve a reservation (set status approved, upload letter if provided).
     * Requires 'approve reservations' permission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */


    public function approve(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'approval_letter' => 'nullable|file|mimes:pdf|max:2048',
            ]);

            // Manual load reservation
            $reservation = Reservation::findOrFail($id);

            $approvedReservation = $this->approvalService->approve(
                $reservation,
                $request->user(),
                $request->file('approval_letter')
            );

            return $this->successResponse(
                new ReservationResource($approvedReservation->fresh(['student', 'approver'])),
                'Reservasi berhasil disetujui.'
            );
        } catch (\Exception $e) {
            Log::error('Error saat menyetujui reservasi ID ' . $id . ': ' . $e->getMessage());
            return $this->exceptionError($e, $e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * Reject a reservation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function reject(Request $request, $id)
    {
        try {
            $user = Auth::user();
            if (!$user->hasPermissionTo('approve reservations')) {
                return response()->json(['success' => false, 'message' => 'Forbidden.'], 403);
            }

            $reservation = Reservation::findOrFail($id);
            if ($reservation->status !== 'pending') {
                return response()->json(['success' => false, 'message' => 'Reservation already processed.'], 400);
            }

            $request->validate([
                'rejection_reason' => 'required|string|max:255',
            ]);

            $reservation->update([
                'status' => 'rejected',
                'approved_by' => $user->id,
                'rejection_reason' => $request->rejection_reason,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reservation rejected successfully.',
                'data' => $reservation->fresh(),
            ], 200);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error rejecting.', 'error' => $e->getMessage()], 500);
        }
    }
}
