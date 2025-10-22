<?php

use App\Http\Controllers\API\Admin\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Admin\UserController;
use App\Http\Controllers\API\RoomsController;
use App\Http\Controllers\API\ScheduleController;
use App\Http\Controllers\API\ApprovalController;

Route::middleware('auth:api')->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // User management
    Route::get('users', [UserController::class, 'index'])->middleware('permission:view users');
    Route::get('users/staff/', [UserController::class, 'showStaff'])->middleware('permission:view users');
    Route::get('users/student/{user}', [UserController::class, 'showStudent'])->middleware('permission:view users');
    Route::get('users/{user}', [UserController::class, 'show'])->middleware('permission:view users');
    Route::post('users', [UserController::class, 'store'])->middleware('permission:create users');
    Route::put('users/{user}', [UserController::class, 'update'])->middleware('permission:edit users');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->middleware('permission:delete users');

    // Staff & student index routes
    Route::get('staff', [UserController::class, 'indexStaff'])->name('staff.index')->middleware('permission:view users');
    Route::get('students', [UserController::class, 'indexStudent'])->name('students.index')->middleware('permission:view users');

    // Rooms
    Route::middleware('permission:view rooms')->group(function () {
        Route::get('/rooms', [RoomsController::class, 'index']);
        Route::get('/rooms/details/{room}', [RoomsController::class, 'show']);
    });
    Route::middleware('permission:create rooms')->group(function () {
        Route::post('/rooms/create', [RoomsController::class, 'store']);
    });
    Route::middleware('permission:edit rooms')->group(function () {
        Route::put('/rooms/edits/{room}', [RoomsController::class, 'update']);
    });
    Route::middleware('permission:delete rooms')->group(function () {
        Route::delete('/rooms/delete/{room}', [RoomsController::class, 'destroy']);
    });

    // Schedule
    Route::middleware('permission:view schedules')->group(function () {
        Route::get('/schedule', [ScheduleController::class, 'index']);
        Route::get('/schedule/details/{schedule}', [ScheduleController::class, 'show']);
    });
    Route::middleware('permission:create schedules')->group(function () {
        Route::post('schedule/create', [ScheduleController::class, 'store']);
    });
    Route::middleware('permission:edit schedules')->group(function () {
        Route::put('schedule/edits/{schedule}', [ScheduleController::class, 'update']);
    });
    Route::middleware('permission:delete schedules')->group(function () {
        Route::delete('schedule/delete/{schedule}', [ScheduleController::class, 'destroy']);
    });

    // Reservations approval
    Route::middleware('permission:view reservations')->group(function () {
        Route::get('/reservations', [ApprovalController::class, 'index']);
    });
    Route::middleware('permission:approve reservations')->group(function () {;
        Route::put('/reservations/{reservation}/approve', [ApprovalController::class, 'approve']);
        Route::put('/reservations/{reservation}/reject', [ApprovalController::class, 'reject']);
    });
});
