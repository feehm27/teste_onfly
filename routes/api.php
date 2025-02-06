<?php

use App\Http\Controllers\OrderTravelController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::resource('order/travels', OrderTravelController::class);
});
