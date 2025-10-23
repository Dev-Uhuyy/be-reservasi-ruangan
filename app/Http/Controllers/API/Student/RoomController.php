<?php

namespace App\Http\Controllers\API\Student;

use App\Http\Controllers\Controller;
use App\Services\RoomServices;
use App\Http\Resources\RoomCollection;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class RoomController extends Controller
{
    protected $roomService;

    public function __construct(RoomServices $roomService)
    {
        $this->roomService = $roomService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'floor', 'date', 'start_time', 'end_time']);
        $rooms = $this->roomService->getAvailableRoomsForStudent($filters);

        return new RoomCollection($rooms);
    }

    public function show(Request $request, Room $room)
    {
        try {
            $filters = $request->only(['date', 'status']);
            $schedules = $this->roomService->getSchedulesForRoomStudent($room, $filters);
            return new RoomResource($room->setAttribute('schedules', $schedules));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching room details.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
