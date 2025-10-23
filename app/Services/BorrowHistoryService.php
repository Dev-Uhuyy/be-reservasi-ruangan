<?php

namespace App\Services;

use App\Models\BookingHistory;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class BorrowHistoryService
{
    /**
     * Mengambil riwayat peminjaman untuk mahasiswa yang sedang login.
     */
      public function getBorrowHistoryForStudent(Request $request): LengthAwarePaginator
    {
        $query = BookingHistory::with(['room', 'student', 'reservation'])
            ->where('student_id', Auth::id())
            // --- FILTER DEFAULT ---
            // Hanya ambil history jika reservation rejected ATAU
            // jika reservation approved DAN usage_status sudah 'used' atau 'unused'.
            ->where(function ($q) {
                $q->whereHas('reservation', fn($subQ) => $subQ->where('status', 'rejected'))
                  ->orWhere(function ($subQ) {
                      $subQ->whereHas('reservation', fn($r) => $r->where('status', 'approved'))
                           ->whereIn('usage_status', ['used', 'unused']);
                  });
            });

        // --- FILTER BERDASARKAN PARAMETER ---
        if ($request->filled('status')) {
            $incomingStatuses = explode(',', $request->status);
            $validStatuses = [];
            // Status yang valid untuk difilter sekarang adalah kombinasi baru
            $knownStatuses = ['rejected', 'approved/used', 'approved/unused'];

            foreach ($incomingStatuses as $status) {
                $trimmedStatus = trim($status);
                if (in_array($trimmedStatus, $knownStatuses)) {
                    $validStatuses[] = $trimmedStatus;
                }
            }

            if (!empty($validStatuses)) {
                $query->where(function ($q) use ($validStatuses) {
                    foreach ($validStatuses as $status) {
                        if ($status === 'rejected') {
                            $q->orWhereHas('reservation', fn($subQ) => $subQ->where('status', 'rejected'));
                        } elseif ($status === 'approved/used') {
                            $q->orWhere(function ($subQ) {
                                $subQ->whereHas('reservation', fn($r) => $r->where('status', 'approved'))
                                     ->where('usage_status', 'used');
                            });
                        } elseif ($status === 'approved/unused') {
                            $q->orWhere(function ($subQ) {
                                $subQ->whereHas('reservation', fn($r) => $r->where('status', 'approved'))
                                     ->where('usage_status', 'unused');
                            });
                        }
                    }
                });
            } else {
                 $query->whereRaw('1 = 0'); // Jika filter tidak valid, kembalikan kosong
            }
        }

        // Filter lain (tanggal, search)
        if ($request->filled('start_date')) {
            $query->whereDate('booking_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('booking_date', '<=', $request->end_date);
        }

        if ($request->filled('search')) {
            $query->whereHas('room', function ($q) use ($request) {
                $q->where('room_name', 'like', '%' . $request->search . '%');
            });
        }

        // Urutkan berdasarkan tanggal booking terbaru
        return $query->orderBy('booking_date', 'desc')->paginate(10);
    }
}
