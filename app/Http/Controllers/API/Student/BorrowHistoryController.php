<?php

namespace App\Http\Controllers\API\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\Student\BorrowHistoryCollection;
use App\Http\Resources\Student\BorrowHistoryResource; // <-- Tambahkan ini
use App\Models\BookingHistory; // <-- Tambahkan ini
use App\Services\BorrowHistoryService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class BorrowHistoryController extends Controller
{
    public function __construct(protected BorrowHistoryService $borrowHistoryService)
    {
    }

    /**
     * Menampilkan riwayat peminjaman untuk mahasiswa yang sedang login.
     */
    public function index(Request $request): BorrowHistoryCollection
    {
        $histories = $this->borrowHistoryService->getBorrowHistoryForStudent($request);
        return new BorrowHistoryCollection($histories);
    }

    /**
     * Menampilkan detail satu riwayat peminjaman.
     */
    public function show(BookingHistory $bookingHistory): BorrowHistoryResource
    {
        // Pastikan mahasiswa hanya bisa melihat riwayat miliknya sendiri
        abort_if($bookingHistory->student_id !== auth()->id(), 403, 'Anda tidak memiliki akses ke riwayat ini.');

        // Eager load semua relasi yang dibutuhkan oleh resource
        $bookingHistory->load(['room', 'student', 'reservation', 'verifier']);

        return new BorrowHistoryResource($bookingHistory);
    }
}

