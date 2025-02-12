<?php

use App\Http\Controllers\TravelOrderController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')
    ->prefix('travel-orders')
    ->group(function () {
        Route::post('/', [TravelOrderController::class, 'store'])->name('travel_orders.store');
        Route::get('/{orderId}', [TravelOrderController::class, 'show'])->name('travel_orders.show');
    });
