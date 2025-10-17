<?php

namespace App\Http\Controllers\API\Staff;

use App\Http\Controllers\Controller;
use App\Http\Resources\Staff\VerificationCollection;
use App\Http\Resources\Staff\VerificationResource;
use App\Models\BookingHistory;
use App\Services\VerificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VerificationController extends Controller
{
    public function __construct(protected VerificationService $verificationService)
    {
    }

    public function index(Request $request): VerificationCollection
    {
        $bookings = $this->verificationService->getPendingVerifications($request);
        return new VerificationCollection($bookings);
    }

    public function show(BookingHistory $bookingHistory): VerificationResource
    {
        $bookingHistory->load(['room', 'student', 'verifier']);
        return new VerificationResource($bookingHistory);
    }

    public function update(Request $request, BookingHistory $bookingHistory): JsonResponse
    {
        $validated = $request->validate([
            'usage_status' => 'required|string|in:used,unused,need_verification',
        ]);

        $updatedBooking = $this->verificationService->updateUsageStatus($bookingHistory, $validated['usage_status']);

        return response()->json([
            'data' => new VerificationResource($updatedBooking->load(['room', 'student', 'verifier'])),
            'meta' => [
                'success' => true,
                'message' => 'Status penggunaan ruangan berhasil diverifikasi!'
            ]
        ]);
    }
}

