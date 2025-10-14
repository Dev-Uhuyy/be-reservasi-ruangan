<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomsController extends Controller
{
    /**
     * Menampilkan semua data ruangan.
     * Endpoint: GET /api/rooms
     */
    public function index(Request $request)
    {
        $query = Room::query();

        if ($request->has('search')) {

            $query->where('room_name', 'like', '%' . $request->search . '%')
                  ->orWhere('facilities', 'like', '%' . $request->search . '%');
        }

        $rooms = $query->latest()->paginate(10);

        return response()->json([
            'data' => RoomResource::collection($rooms),
            'meta' => [
                'status_code' => 200,
                'success' => true,
                'message' => 'Rooms fetched successfully!'
            ]
        ], 200);
    }

    /**
     * Menyimpan data ruangan baru.
     * Endpoint: POST /api/rooms/create
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'room_name' => 'required|string|max:100|unique:rooms,room_name',
            'floor' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1',
            'facilities' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => ['errors' => $validator->errors()],
                'meta' => [
                    'status_code' => 422,
                    'success' => false,
                    'message' => 'Validation failed!'
                ]
            ], 422);
        }

        $room = Room::create($validator->validated());

        return response()->json([
            'data' => new RoomResource($room),
            'meta' => [
                'status_code' => 201,
                'success' => true,
                'message' => 'Room created successfully!'
            ]
        ], 201);
    }

    /**
     * Menampilkan detail satu ruangan.
     * Endpoint: GET /api/rooms/details/{room}
     */
    public function show(Room $room)
    {
        return response()->json([
            'data' => new RoomResource($room),
            'meta' => [
                'status_code' => 200,
                'success' => true,
                'message' => 'Room fetched successfully!'
            ]
        ], 200);
    }

    /**
     * Memperbarui data ruangan.
     * Endpoint: PUT /api/rooms/edits/{room}
     */
    public function update(Request $request, Room $room)
    {
        $validator = Validator::make($request->all(), [
        
            'room_name' => 'required|string|max:100|unique:rooms,room_name,' . $room->id,
            'floor' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1',
            'facilities' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => ['errors' => $validator->errors()],
                'meta' => [
                    'status_code' => 422,
                    'success' => false,
                    'message' => 'Validation failed!'
                ]
            ], 422);
        }

        $room->update($validator->validated());

        return response()->json([
            'data' => new RoomResource($room),
            'meta' => [
                'status_code' => 200,
                'success' => true,
                'message' => 'Room updated successfully!'
            ]
        ], 200);
    }

    /**
     * Menghapus data ruangan.
     * Endpoint: DELETE /api/rooms/delete/{room}
     */
    public function destroy(Room $room)
    {
        $room->delete();

        return response()->json([
            'data' => null,
            'meta' => [
                'status_code' => 200,
                'success' => true,
                'message' => 'Room deleted successfully!'
            ]
        ], 200);
    }
}

