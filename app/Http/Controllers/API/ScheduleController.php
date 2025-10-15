<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ScheduleRequest;
use App\Http\Resources\Admin\ScheduleCollection;
use App\Http\Resources\Admin\ScheduleResource;
use App\Models\Schedule;
use App\Services\RoomService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;



class ScheduleController extends Controller
{
    public function __construct(protected RoomService $roomService) {}

    public function index(Request $request): ScheduleCollection
    {
        $schedules = $this->roomService->getAllSchedules($request);
        return new ScheduleCollection($schedules);
    }

    public function store(ScheduleRequest $request): JsonResponse
    {
        $schedule = $this->roomService->createSchedule($request->validated());
        return response()->json([
            'data' => new ScheduleResource($schedule),
            'meta' => ['success' => true, 'message' => 'Jadwal berhasil ditambahkan!']
        ], 201);
    }

    public function show(Schedule $schedule): JsonResponse
    {
        return response()->json([
            // Hanya muat relasi 'room' yang memang ada
            'data' => new ScheduleResource($schedule->load(['room'])),
            'meta' => ['success' => true, 'message' => 'Detail jadwal berhasil diambil!']
        ]);
    }

    public function update(ScheduleRequest $request, Schedule $schedule): JsonResponse
    {
        $updatedSchedule = $this->roomService->updateSchedule($schedule, $request->validated());

        // Hanya muat relasi 'room' yang memang ada
        $updatedSchedule->load(['room']);

        return response()->json([
            'data' => new ScheduleResource($updatedSchedule),
            'meta' => ['success' => true, 'message' => 'Jadwal berhasil diperbarui!']
        ]);
    }

    public function destroy(Schedule $schedule): JsonResponse
    {
        $this->roomService->deleteSchedule($schedule);
        return response()->json([
            'data' => null,
            'meta' => ['success' => true, 'message' => 'Jadwal berhasil dihapus!']
        ]);
    }
}

