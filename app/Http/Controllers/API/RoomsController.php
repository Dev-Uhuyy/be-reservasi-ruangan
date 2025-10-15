<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoomRequest;
use App\Http\Resources\RoomCollection;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use App\Services\RoomService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class RoomsController extends Controller
{
    // Inject RoomService melalui constructor agar bisa digunakan di semua method
    public function __construct(protected RoomService $roomService)
    {
    }

    /**
     * Menampilkan semua data ruangan.
     */
    public function index(Request $request): RoomCollection
    {
        // Delegasikan tugas ke RoomService
        $rooms = $this->roomService->getAllRooms($request);
        return new RoomCollection($rooms);
    }

    /**
     * Menyimpan data ruangan baru.
     */
    public function store(RoomRequest $request): JsonResponse
    {
        // Validasi terjadi secara otomatis oleh RoomRequest.
        // Jika gagal, controller tidak akan pernah menjalankan kode ini.
        $room = $this->roomService->createRoom($request->validated());

        return response()->json([
            'data' => new RoomResource($room),
            'meta' => [
                'status_code' => 201,
                'success' => true,
                'message' => 'Ruangan berhasil ditambahkan!'
            ]
        ], 201);
    }

    /**
     * Menampilkan detail satu ruangan.
     */
    public function show(Room $room): JsonResponse
    {
        return response()->json([
            'data' => new RoomResource($room),
            'meta' => [
                'status_code' => 200,
                'success' => true,
                'message' => 'Data ruangan berhasil diambil!'
            ]
        ], 200);
    }

    /**
     * Memperbarui data ruangan.
     */
    public function update(RoomRequest $request, Room $room): JsonResponse
    {
        // Validasi juga terjadi secara otomatis oleh RoomRequest.
        $updatedRoom = $this->roomService->updateRoom($room, $request->validated());

        return response()->json([
            'data' => new RoomResource($updatedRoom),
            'meta' => [
                'status_code' => 200,
                'success' => true,
                'message' => 'Data ruangan berhasil diperbarui!'
            ]
        ], 200);
    }

    /**
     * Menghapus data ruangan.
     */
    public function destroy(Room $room): JsonResponse
    {
        $this->roomService->deleteRoom($room);

        return response()->json([
            'data' => null,
            'meta' => [
                'status_code' => 200,
                'success' => true,
                'message' => 'Ruangan berhasil dihapus!'
            ]
        ], 200);
    }
}

