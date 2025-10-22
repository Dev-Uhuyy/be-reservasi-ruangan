<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Student\RoomController;
use App\Http\Controllers\API\Student\ReservationController;

Route::middleware(['auth:api', 'permission:view reservations'])->prefix('student')->group(function () {
    Route::get('/rooms', [RoomController::class, 'index']);
    Route::get('/rooms/{room}', [RoomController::class, 'show']);
});

Route::middleware(['auth:api', 'permission:create reservations'])->prefix('student')->group(function () {
    Route::post('/reservations', [ReservationController::class, 'store']);
});
