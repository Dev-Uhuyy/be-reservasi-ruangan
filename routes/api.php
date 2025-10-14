<?php

use App\Http\Controllers\API\RoomsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\UserController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('/rooms/search', [RoomsController::class, 'search']);
Route::get('/rooms', [RoomsController::class, 'index']);
Route::get('/rooms/details/{room}', [RoomsController::class, 'show']);
Route::post('/rooms/create', [RoomsController::class, 'store']);
Route::put('/rooms/edits/{room}', [RoomsController::class, 'update']);
Route::delete('/rooms/delete/{room}', [RoomsController::class, 'destroy']);


Route::middleware('auth:api')->prefix('auth')->group(function () {
    Route::get('me', [AuthController::class, 'profile']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::put('me', [AuthController::class, 'updateProfile']);
    Route::post('me/avatar', [AuthController::class, 'updateAvatar']);
});

Route::middleware('auth:api')->prefix('admin')->group(function () {
    Route::middleware('permission:view users')->group(function () {
        Route::get('users/all', [UserController::class, 'index']);
        Route::get('users/students', [UserController::class, 'student']);
        Route::get('users/staff', [UserController::class, 'staff']);

        //Route

    });
});
