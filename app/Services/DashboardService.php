<?php

namespace App\Services;

use App\Models\BookingHistory;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
        $totalCheckinToday = BookingHistory::where('usage_status', 'used')
        ->whereDate('verified_at', $today)    
        ->count();

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
            ->with('student:id,name', 'details.room:id,room_name', 'details.schedule:id,date,start_time') 
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

    public function dashboardStaff(User $staff){

        $today = now()->toDateString();

        // Logic for staff dashboard
        // 1. total ruangan  yang tersedia
        $totalAvailableRoomStaff = Room::where('status','active')
            ->where('floor', $staff->floor)
            ->count();
        
        // 2. total ruangan yang aktif
        $jumlahRuanganDigunakan = Room::where('floor', $staff->floor)
            ->whereHas('schedules', function ($q) {
                $q->where('date', now()->toDateString())
                  ->where('status', 'booked')
                  ->where('start_time', '<=', now()->toTimeString())
                  ->where('end_time', '>=', now()->toTimeString());
            })->count();
        
        // 3. total menunggu verifikasi
        $totalMenungguVerifikasi = BookingHistory::where('usage_status','need_verification')
            ->whereDate('booking_date', $today)
            ->whereHas('room', function ($query) use ($staff) {
                $query->where('floor', $staff->floor);
            })
            ->count();

        // 4. list yang harus di verifikasi sesuai floor
        $listVerifikasiStaff = BookingHistory::where('usage_status', 'need_verification')
            ->whereDate('booking_date', $today)
            ->whereHas('room', function ($query) use ($staff) {
                $query->where('floor', $staff->floor);
            })
            ->with(['room:id,room_name,floor', 'student:id,name', 'reservation:id,purpose'])
            ->get();

        return [
            'jumlah_ruangan_tersedia' => $totalAvailableRoomStaff,
            'jumlah_ruangan_digunakan' => $jumlahRuanganDigunakan,
            'jumlah_menunggu_verifikasi' => $totalMenungguVerifikasi,
            'list_verifikasi' => $listVerifikasiStaff
        ];
    }

    public function dashboardStudent(User $student){
        // Logic for student dashboard
        // 1. total peminjaman ruangan
        $totalPeminjamanRuangan = Room::where('student_id', $student->id)->count();
        
        // 2. total ruangan yang di setujui
        $totalRuanganApproved = Reservation::where('student_id', $student->id)
            ->where('status','approved')
            ->count();

        // 3. Menunggu Persetujuan
        $totalMenungguPersetujuan = Reservation::where('student_id', $student->id)
            ->where('status','pending')
            ->count();

        // 4. Ditolak
        $totalDiReject = Reservation::where('student_id', $student->id)
            ->where('status','rejected')
            ->count();

        // 5. Peminjaman Mendatang
        $peminjamanMendatang = Reservation::where('student_id', $student->id) 
            ->where('status', 'approved') // 2. Filter Status
            ->whereHas('details.schedule', function ($query) { // 3. Filter Cerdas Berdasarkan Relasi
                $query->where('date', '>=', now()->toDateString());
            })
            ->with('details.room:id,room_name', 'details.schedule:id,date,start_time') 
            ->latest('created_at') // 5. Pengurutan
            ->limit(3) // 6. Pembatasan Hasil
            ->get(); // 7. Eksekusi

        return [
            'stats' => [
                'total_peminjaman' => $totalPeminjamanRuangan,
                'total_ruangan_disetujui' => $totalRuanganApproved,
                'total_menunggu_persetujuan' => $totalMenungguPersetujuan,
                'total_ditolak' => $totalDiReject
            ],
            'peminjaman_mendatang' =>  $peminjamanMendatang
        ];
    }
}
