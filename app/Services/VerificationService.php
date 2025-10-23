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
            'verified_at' => now(),
        ]);

        return $bookingHistory;
    }

    /**
     * Mengambil riwayat verifikasi untuk staff yang sedang login.
     */
    public function getVerificationHistoryForStaff(Request $request): LengthAwarePaginator
    {
        $query = BookingHistory::with(['room', 'student'])
            // Wajib: Hanya tampilkan riwayat milik staff yang login
            ->where('verified_by', Auth::id())
            // Wajib: Hanya tampilkan status final
            ->whereIn('usage_status', ['used', 'unused']);

        // Filter opsional berdasarkan status
        if ($request->filled('status')) {
            $query->where('usage_status', $request->status);
        }

        // Filter opsional berdasarkan rentang tanggal verifikasi
        if ($request->filled('start_date')) {
            $query->whereDate('verified_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('verified_at', '<=', $request->end_date);
        }

        // Filter opsional berdasarkan nama ruangan
        if ($request->filled('search')) {
            $query->whereHas('room', function ($q) use ($request) {
                $q->where('room_name', 'like', '%' . $request->search . '%');
            });
        }

        // Urutkan berdasarkan yang paling baru diverifikasi
        return $query->orderBy('verified_at', 'desc')->paginate(15);
    }
}
