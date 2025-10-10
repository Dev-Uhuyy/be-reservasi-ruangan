<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// route yang hanya boleh diakses admin
Route::get('/admin-only', [AdminController::class, 'index'])
    ->middleware(['auth:api','role:admin']);

// route yang memeriksa permission spesifik
Route::get('/users', [UserController::class, 'index'])
    ->middleware(['auth:api','permission:view users']);


Route::middleware('auth:api')->group(function () {
    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('logout', [AuthController::class, 'logout']);
});
