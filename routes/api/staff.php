<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Staff\VerificationController;
use App\Http\Controllers\API\RoomsController;
use App\Http\Controllers\API\Staff\HistoryController;

Route::middleware(['auth:api', 'permission:verify reservations'])->prefix('staff')->group(function () {
    Route::get('/verifications', [VerificationController::class, 'index']);
    Route::get('/verifications/{bookingHistory}', [VerificationController::class, 'show']);
    Route::put('/verifications/{bookingHistory}', [VerificationController::class, 'update']);

    // Rooms (read-only for staff)
    Route::middleware('permission:view rooms')->group(function () {
        Route::get('/rooms', [RoomsController::class, 'index']);
        Route::get('/rooms/details/{room}', [RoomsController::class, 'show']);
    });

    Route::middleware('permission:view verification history')->group(function () {
        Route::get('/history', [HistoryController::class, 'index']);
    });

});
