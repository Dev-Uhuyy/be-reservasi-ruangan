<?php

namespace App\Services;

use App\Models\Room;

class RoomServices
{
    public function getAvailableRoomsForStudent(array $filters)
    {
        $query = Room::query()->where('status', 'active');

        if (!empty($filters['search'])) {
            $query->where('room_name', 'like', '%' . $filters['search'] . '%'); // BENAR
        }

        if (!empty($filters['floor'])) {
            $query->where('floor', $filters['floor']);
        }

        if (!empty($filters['date']) && !empty($filters['start_time']) && !empty($filters['end_time'])) {
            $query->whereDoesntHave('schedules', function ($q) use ($filters) {
                $q->where('date', $filters['date'])
                    ->where(function ($q) use ($filters) {
                        $q->whereBetween('start_time', [$filters['start_time'], $filters['end_time']])
                            ->orWhereBetween('end_time', [$filters['start_time'], $filters['end_time']])
                            ->orWhere(function ($q) use ($filters) {
                                $q->where('start_time', '<=', $filters['start_time'])
                                    ->where('end_time', '>=', $filters['end_time']);
                            });
                    });
            });
        }

        return $query->paginate(6); // Pastikan ini menggunakan paginate, bukan get
    }

    public function getSchedulesForRoomStudent(Room $room, array $filters)
    {
        $query = $room->schedules()->select('id', 'date', 'start_time', 'end_time', 'status');

        if (!empty($filters['date'])) {
            $query->where('date', $filters['date']);
        }

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'available') {
                $query->where('status', 'available');
            }
        }

        return $query->orderBy('start_time')->get();
    }
}
