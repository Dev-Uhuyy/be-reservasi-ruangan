<?php

use App\Http\Controllers\API\Student\BorrowHistoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Student\RoomController;
use App\Http\Controllers\API\Student\ReservationController;

// --- RUTE STUDENT (UNTUK RIWAYAT PEMINJAMAN) ---
Route::middleware(['auth:api', 'role:student'])->prefix('student')->group(function () {
    Route::get('/rooms', [RoomController::class, 'index']);
    Route::get('/rooms/{room}', [RoomController::class, 'show']);
    Route::post('/reservations', [ReservationController::class, 'store']);

    // Endpoint untuk daftar riwayat
    Route::get('/borrow-history', [BorrowHistoryController::class, 'index']);
    // Endpoint untuk detail riwayat
    Route::get('/borrow-history/{bookingHistory}', [BorrowHistoryController::class, 'show']);
});
