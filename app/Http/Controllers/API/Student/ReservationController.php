<?php

namespace App\Http\Controllers\API\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReservationRequest;
use App\Services\ReservationService;
use App\Http\Resources\ReservationResource;
use Illuminate\Support\Facades\Log;
use Exception;

class ReservationController extends Controller
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    /**
     * Store a newly created reservation.
     */
    public function store(StoreReservationRequest $request)
    {
        try {
            // Panggil service dengan data yang sudah divalidasi dan user yang login
            $reservation = $this->reservationService->createStudentReservation(
                $request->user(),
                $request->validated()
            );

            // Muat relasi yang diperlukan sebelum dikirim ke Resource
            $reservation->load('student', 'reservationDetails.schedule');

            return $this->successResponse(
                new ReservationResource($reservation),
                'Reservasi berhasil dibuat.',
                201
            );
        } catch (Exception $e) {
            // Catat error untuk debugging
            return $this->exceptionError($e, 'Gagal membuat reservasi.');
        }
    }
}
