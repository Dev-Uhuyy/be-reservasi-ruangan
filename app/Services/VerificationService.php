<?php

namespace App\Services;

use App\Models\BookingHistory;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class VerificationService
{
    /**
     * Mengambil daftar pemesanan yang menunggu verifikasi (verified_by is NULL).
     */
    public function getPendingVerifications(Request $request): LengthAwarePaginator
    {
        $query = BookingHistory::with(['room', 'student']); // Cari yang belum diverifikasi

        // Fungsionalitas pencarian berdasarkan nama ruangan
        if ($request->has('search')) {
            $query->whereHas('room', function ($q) use ($request) {
                $q->where('room_name', 'like', '%' . $request->search . '%');
            });
        }

        return $query->latest()->paginate(3);
    }

    /**
     * Memperbarui status verifikasi penggunaan ruangan.
     * @param string $usageStatus Status baru ('used' atau 'unused').
     */
    public function updateUsageStatus(BookingHistory $bookingHistory, string $usageStatus): BookingHistory
    {
        $bookingHistory->update([
            'usage_status' => $usageStatus,
            'verified_by' => Auth::id(), // ID staff yang sedang login
            'verified_at' => now(),      // Waktu saat ini
        ]);

        return $bookingHistory;
    }
}
