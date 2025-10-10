<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;




Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('logout', [AuthController::class, 'logout']);
});
