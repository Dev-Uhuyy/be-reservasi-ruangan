<?php

namespace App\Services;

use App\Models\BookingHistory;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Create a new class instance.
     * 
     */
    public function dashboardAdmin(){
        $today = now()->toDateString();
        
        // total reservasi hari ini
        $totalReservationTodey = Reservation::whereDate('created_at', $today)->count();

        // total checkin hari ini
        $totalCheckinToday = BookingHistory::where('usage_status', 'used')->count();

        // total ruang tersedia
        $totalAvailableRoom = Room::where('status','active')->count();

        $grafikPenggunaan = BookingHistory::select('room_id', DB::raw('count(*) as total'))
            ->join('rooms', 'booking_histories.room_id', '=', 'rooms.id')
            ->groupBy('room_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->with('room:id,room_name') // Eager load nama ruangan
            ->get();

        $antrianPersetujuan = Reservation::where('status', 'pending')
            ->with('student:id,name', 'details.room:id,room_name') 
            ->latest()
            ->limit(10)
            ->get(); 
            
            
        return [
            'total_reservasi_hari_ini' => $totalReservationTodey,
            'total_checkin_hari_ini' => $totalCheckinToday,
            'total_ruangan_tersedia' => $totalAvailableRoom,
            'grafik_penggunaan_ruangan' => $grafikPenggunaan,
            'list_antrian_persetujuan' => $antrianPersetujuan
        ];
    } 

    public function dashboardStaff(){
        // Logic for staff dashboard
    }

    public function dashboardStudent(){
        // Logic for student dashboard
    }
}
