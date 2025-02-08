<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TravelOrderController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::get('/travel-orders', [TravelOrderController::class, 'index']);
    Route::post('/travel-orders', [TravelOrderController::class, 'store']);
    Route::get('/travel-orders/{id}', [TravelOrderController::class, 'show']);
    Route::put('/travel-orders/{id}/status', [TravelOrderController::class, 'updateStatus']);
    Route::delete('/travel-orders/{id}/cancel', [TravelOrderController::class, 'cancel']);
    Route::get('/notifications', [TravelOrderController::class, 'notifications']);
    Route::put('/notifications/{id}', [TravelOrderController::class, 'markNotificationAsRead']);
});
