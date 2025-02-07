<?php

use App\Http\Controllers\{AuthController,OrderTravelController};
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::resource('order/travels', OrderTravelController::class);
});
