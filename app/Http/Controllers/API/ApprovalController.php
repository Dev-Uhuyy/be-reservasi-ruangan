<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Exception;
use App\Services\ApprovalService;
use App\Http\Resources\ReservationResource;
use App\Http\Requests\ApproveReservationRequest;
use App\Http\Requests\RejectReservationRequest;
use App\Exceptions\ReservationAlreadyProcessedException;
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
        $query = Reservation::with(['student', 'approver'])
            ->filter($request->only(['status', 'student_id', 'search'])); 
        $perPage = $request->get('per_page', 15);
        $reservations = $query->latest()->paginate($perPage); 
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

            return $this->successResponse(
                new ReservationResource($approvedReservation),
                'Reservation approved successfully.'
            );
        } catch (ReservationAlreadyProcessedException $e) {
            return $e->render($request);
        } catch (Exception $e) {
            return $this->exceptionError($e, 'Error approving reservation.', 500);
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

            return $this->successResponse(
                new ReservationResource($rejectedReservation),
                'Reservation rejected successfully.'
            );
        } catch (ReservationAlreadyProcessedException $e) {
            return $e->render($request);
        } catch (Exception $e) {
            return $this->exceptionError($e, 'Error rejecting reservation.', 500);
        }
    }
}
