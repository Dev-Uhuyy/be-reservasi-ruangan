<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RoomsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Admin\UserController;



Route::post('/register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


Route::middleware('auth:api')->prefix('auth')->group(function () {
    Route::get('me', [AuthController::class, 'profile']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::put('me', [AuthController::class, 'updateProfile']);
    Route::post('me/avatar', [AuthController::class, 'updateAvatar']);
});

Route::middleware('auth:api')->prefix('admin')->group(function () {
    Route::get('users', [UserController::class, 'index'])
        ->middleware('permission:view users');
    Route::get('users/{user}', [UserController::class, 'show'])
        ->middleware('permission:view users');
    Route::post('users', [UserController::class, 'store'])
        ->middleware('permission:create users');
    Route::put('users/{user}', [UserController::class, 'update'])
        ->middleware('permission:edit users');
    Route::delete('users/{user}', [UserController::class, 'destroy'])
        ->middleware('permission:delete users');




    Route::get('staff/{user}', [UserController::class, 'showStaff'])
        ->middleware('permission:view users');
    Route::get('students/{user}', [UserController::class, 'showStudent'])
        ->middleware('permission:view users');
    Route::get('staff', [UserController::class, 'indexStaff'])
        ->name('staff.index')
        ->middleware('permission:view users');
    // Endpoint: GET /api/admin/students
    Route::get('students', [UserController::class, 'indexStudent'])
        ->name('students.index')
        ->middleware('permission:view users');



    // Izin untuk melihat ruangan
    Route::middleware('permission:view rooms')->group(function () {
        Route::get('/rooms', [RoomsController::class, 'index']);
        Route::get('/rooms/details/{room}', [RoomsController::class, 'show']);
    });
    // Izin untuk membuat ruangan baru
    Route::middleware('permission:create rooms')->group(function () {
        Route::post('/rooms/create', [RoomsController::class, 'store']);
    });
    // Izin untuk mengedit ruangan
    Route::middleware('permission:edit rooms')->group(function () {
        Route::put('/rooms/edits/{room}', [RoomsController::class, 'update']);
    });
    // Izin untuk menghapus ruangan
    Route::middleware('permission:delete rooms')->group(function () {
        Route::delete('/rooms/delete/{room}', [RoomsController::class, 'destroy']);
    });
});
