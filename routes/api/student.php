<?php

use App\Http\Controllers\API\Student\BorrowHistoryController;
use App\Http\Controllers\API\Student\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Student\RoomController;
use App\Http\Controllers\API\Student\ReservationController;

Route::middleware(['auth:api', 'role:student'])->prefix('student')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/rooms', [RoomController::class, 'index']);
    Route::get('/rooms/{room}', [RoomController::class, 'show']);
    Route::post('/reservations', [ReservationController::class, 'store']);

    // Endpoint untuk daftar riwayat
    Route::get('/borrow-history', [BorrowHistoryController::class, 'index']);
    Route::get('/borrow-history/{bookingHistory}', [BorrowHistoryController::class, 'show']);
});
