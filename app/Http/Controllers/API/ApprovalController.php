<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Exception;
use App\Services\ApprovalService; // Import Service
use App\Http\Resources\ReservationResource; // Import Resource
use App\Http\Requests\ApproveReservationRequest; // Import Form Request
use App\Http\Requests\RejectReservationRequest; // Import Form Request
use App\Exceptions\ReservationAlreadyProcessedException; // Import Exception Kustom
use Illuminate\Http\JsonResponse;

class ApprovalController extends Controller
{
    /**
     * Inject ApprovalService via constructor.
     */
    public function __construct(protected ApprovalService $approvalService) {}

    /**
     * Display a listing of reservations.
     */
    public function index(Request $request): JsonResponse
    {
        // Pindahkan logic query ke model/service. Di sini kita gunakan Local Scope.
        $query = Reservation::with(['student', 'approver'])
            ->filter($request->only(['status', 'student_id', 'search'])); // Gunakan scope

        $perPage = $request->get('per_page', 15);
        $reservations = $query->latest()->paginate($perPage); // Tambah latest()

        // Gunakan API Resource Collection untuk transformasi dan respons standar
        return ReservationResource::collection($reservations)
            ->additional([
                'success' => true,
                'message' => $reservations->isEmpty() ? 'No reservations found.' : 'List retrieved successfully.',
            ])
            ->response();
    }

    /**
     * Approve a reservation.
     * Validasi dan Otorisasi ditangani oleh ApproveReservationRequest.
     * Route-Model Binding ($reservation) otomatis melakukan findOrFail.
     */
    public function approve(ApproveReservationRequest $request, Reservation $reservation): JsonResponse
    {
        try {
            $approvedReservation = $this->approvalService->approveReservation(
                $reservation,
                $request->user()
            );

            // Kembalikan data menggunakan Resource
            return (new ReservationResource($approvedReservation))
                ->additional(['success' => true, 'message' => 'Reservation approved successfully.'])
                ->response();
        } catch (ReservationAlreadyProcessedException $e) {
            // Tangani exception kustom jika reservasi sudah diproses
            return $e->render($request);
        } catch (Exception $e) {
            // Tangani error umum
            return $this->apiError('Error approving reservation.', $e);
        }
    }

    /**
     * Reject a reservation.
     * Validasi dan Otorisasi ditangani oleh RejectReservationRequest.
     * Route-Model Binding ($reservation) otomatis melakukan findOrFail.
     */
    public function reject(RejectReservationRequest $request, Reservation $reservation): JsonResponse
    {
        try {
            $rejectedReservation = $this->approvalService->rejectReservation(
                $reservation,
                $request->user(),
                $request->rejection_reason
            );


            // Kembalikan data menggunakan Resource
            return (new ReservationResource($rejectedReservation))
                ->additional(['success' => true, 'message' => 'Reservation rejected successfully.'])
                ->response();
        } catch (ReservationAlreadyProcessedException $e) {
            // Tangani exception kustom jika reservasi sudah diproses
            return $e->render($request);
        } catch (Exception $e) {
            // Tangani error umum
            return $this->apiError('Error rejecting reservation.', $e);
        }
    }

    /**
     * Helper untuk response error standar.
     */
    protected function apiError(string $message, Exception $e, int $code = 500): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            // Tampilkan pesan error hanya di mode debug
            'error' => config('app.debug') ? $e->getMessage() : 'An internal error occurred.',
        ], $code);
    }
}
