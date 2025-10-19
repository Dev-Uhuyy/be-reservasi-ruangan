<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->prefix('auth')->group(function () {
    Route::get('me', [AuthController::class, 'profile']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::put('me', [AuthController::class, 'updateProfile']);
    Route::post('me/avatar', [AuthController::class, 'updateAvatar']);
    Route::put('reset-password', [AuthController::class, 'changePassword']);
});
