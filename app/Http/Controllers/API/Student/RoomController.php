<?php

namespace App\Http\Controllers\API\Student;

use App\Http\Controllers\Controller;
use App\Services\RoomServices;
use App\Http\Resources\RoomCollection;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RoomController extends Controller
{
    protected $roomService;

    public function __construct(RoomServices $roomService)
    {
        $this->roomService = $roomService;
    }

    /**
     * Menampilkan daftar ruangan yang tersedia untuk student
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'floor', 'date', 'start_time', 'end_time']);
        $rooms = $this->roomService->getAvailableRoomsForStudent($filters);

        // Menggunakan RoomCollection yang sudah ditangani di base Controller
        return new RoomCollection($rooms);
    }

    /**
     * Menampilkan detail ruangan beserta jadwalnya
     */
    public function show(Request $request, Room $room): JsonResponse
    {
        try {
            $filters = $request->only(['date', 'status']);
            $schedules = $this->roomService->getSchedulesForRoomStudent($room, $filters);
            
            $room->setAttribute('schedules', $schedules);
            
            return $this->successResponse(
                new RoomResource($room),
                'Detail ruangan berhasil diambil!'
            );
        } catch (\Exception $e) {
            return $this->exceptionError($e, 'Gagal mengambil detail ruangan', 500);
        }
    }
}