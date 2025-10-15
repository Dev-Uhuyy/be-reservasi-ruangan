<?php

namespace App\Services;

use App\Models\Room;
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
     * @param array $validatedData Data yang sudah tervalidasi.
     */
    public function createRoom(array $validatedData): Room
    {
        return Room::create($validatedData);
    }

    /**
     * Memperbarui data ruangan yang ada.
     * @param Room $room Model ruangan yang akan diperbarui.
     * @param array $validatedData Data yang sudah tervalidasi.
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
}
