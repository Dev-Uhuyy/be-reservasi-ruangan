<?php

namespace App\Services;

use App\Models\Room;
use App\Models\Schedule; // <-- Tambahkan model Schedule
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class RoomService
{
    /**
     * Mengambil semua data ruangan dengan paginasi dan fungsionalitas pencarian.
     */
    public function getAllRooms(Request $request): LengthAwarePaginator
    {
        $query = Room::query();

        if ($request->has('search')) {
            $query->where('room_name', 'like', '%' . $request->search . '%')
                  ->orWhere('facilities', 'like', '%' . $request->search . '%');
        }

        return $query->latest()->paginate(10);
    }

    /**
     * Membuat data ruangan baru.
     */
    public function createRoom(array $validatedData): Room
    {
        return Room::create($validatedData);
    }

    /**
     * Memperbarui data ruangan yang ada.
     */
    public function updateRoom(Room $room, array $validatedData): Room
    {
        $room->update($validatedData);
        return $room;
    }

    /**
     * Menghapus data ruangan.
     */
    public function deleteRoom(Room $room): void
    {
        $room->delete();
    }

    // --- LOGIKA UNTUK JADWAL DIMULAI DI SINI ---

    /**
     * Mengambil semua data jadwal dengan paginasi dan pencarian.
     */
    public function getAllSchedules(Request $request): LengthAwarePaginator
    {
        // Query ke relasi room untuk pencarian
        $query = Schedule::with(['room']); // Eager load relasi room

        if ($request->has('search')) {
            // whereHas digunakan untuk memfilter jadwal berdasarkan nama ruangannya
            $query->whereHas('room', function ($q) use ($request) {
                $q->where('room_name', 'like', '%' . $request->search . '%');
            });
        }

        return $query->latest()->paginate(10);
    }

    /**
     * Membuat data jadwal baru.
     * @param array $validatedData Data yang sudah tervalidasi dari ScheduleRequest.
     */
    public function createSchedule(array $validatedData): Schedule
    {
        return Schedule::create($validatedData);
    }

    /**
     * Memperbarui data jadwal yang ada.
     * @param Schedule $schedule Model jadwal yang akan diperbarui.
     * @param array $validatedData Data yang sudah tervalidasi dari ScheduleRequest.
     */
    public function updateSchedule(Schedule $schedule, array $validatedData): Schedule
    {
        $schedule->update($validatedData);
        return $schedule;
    }

    /**
     * Menghapus data jadwal.
     * @param Schedule $schedule Model jadwal yang akan dihapus.
     */
    public function deleteSchedule(Schedule $schedule): void
    {
        $schedule->delete();
    }
}

