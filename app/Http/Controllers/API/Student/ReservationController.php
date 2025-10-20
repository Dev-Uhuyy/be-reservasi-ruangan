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

            /**
             * KODE YANG SUDAH DIPERBAIKI:
             * Gunakan ->additional() untuk menambahkan metadata,
             * dan panggil SEBELUM ->response().
             */
            return (new ReservationResource($reservation))
                ->additional(['meta' => [
                    'message' => 'Reservasi berhasil dibuat.',
                    'success' => true,
                    'status_code' => 201,
                ]])
                ->response()
                ->setStatusCode(201);
        } catch (Exception $e) {
            // Catat error untuk debugging
            Log::error('Reservation failed: ' . $e->getMessage());

            // Jika exception karena race condition (sesuai pesan di service)
            if ($e->getMessage() === "Jadwal sudah tidak tersedia.") {
                // Kembalikan respons error 409 Conflict
                return response()->json([
                    'message' => $e->getMessage()
                ], 409); // 409 Conflict
            }

            // Untuk error lainnya
            return response()->json([
                'message' => 'Terjadi kesalahan internal saat memproses reservasi Anda.'
            ], 500);
        }
    }
}
