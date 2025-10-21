<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Student\RoomController;
use App\Http\Controllers\API\Student\ReservationController;

Route::get('/student/rooms', [RoomController::class, 'index']);
Route::get('/student/rooms/{room}', [RoomController::class, 'show']);
Route::post('/student/reservations', [ReservationController::class, 'store'])
    ->middleware('auth:api'); // Pastikan menggunakan middleware auth
// wait dashboard satune blm