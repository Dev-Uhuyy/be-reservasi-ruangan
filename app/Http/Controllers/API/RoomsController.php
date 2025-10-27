<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoomRequest;
use App\Http\Resources\Admin\RoomCollection;
use App\Http\Resources\Admin\RoomResource;
use App\Models\Room;
use App\Services\RoomService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RoomsController extends Controller
{
    public function __construct(protected RoomService $roomService)
    {
    }

    /**
     * Menampilkan semua data ruangan.
     */
     /**
     *  @OA\Get(
     *  path="/admin/rooms",
     *  summary="Melihat Ruangan",
     *  description="Endpoint untuk melihat data ruangan",
     *  tags={"Admin - Manajemen Ruangan"},
     *  security={{"bearerAuth":{}}},
     *  @OA\Response(
     *  response=200,
     *      description="Data Berhasil di tampilkan.",
     *      @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     * ),
     * @OA\Response(
     *  response=500,
     *      description="Kesalahan Server.",
     *      @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     *
     * )
     */
    public function index(Request $request): RoomCollection
    {
        $rooms = $this->roomService->getAllRooms($request);
        return new RoomCollection($rooms);
    }

    /**
     * Menyimpan data ruangan baru.
     */
    public function store(RoomRequest $request): JsonResponse
    {
        $room = $this->roomService->createRoom($request->validated());

        // Menggunakan template dari Controller
        return $this->successResponse(
            new RoomResource($room),
            'Ruangan berhasil ditambahkan!',
            201
        );
    }

    /**
     * Menampilkan detail satu ruangan.
     */
    public function show(Room $room): JsonResponse
    {
        // Menggunakan template dari Controller
        return $this->successResponse(
            new RoomResource($room),
            'Data ruangan berhasil diambil!'
        );
    }

    /**
     * Memperbarui data ruangan.
     */
    public function update(RoomRequest $request, Room $room): JsonResponse
    {
        $updatedRoom = $this->roomService->updateRoom($room, $request->validated());

        // Menggunakan template dari Controller
        return $this->successResponse(
            new RoomResource($updatedRoom),
            'Data ruangan berhasil diperbarui!'
        );
    }

    /**
     * Menghapus data ruangan.
     */
    public function destroy(Room $room): JsonResponse
    {
        $this->roomService->deleteRoom($room);

        // Menggunakan template dari Controller
        return $this->successResponse(
            null,
            'Ruangan berhasil dihapus!'
        );
    }
}
