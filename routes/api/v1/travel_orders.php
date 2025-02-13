<?php

use App\Http\Controllers\TravelOrderController;
use App\Http\Controllers\TravelOrderStatusController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')
    ->prefix('travel-orders')
    ->group(function () {
        Route::get('/', [TravelOrderController::class, 'index'])->name('travel_orders.index');
        Route::post('/', [TravelOrderController::class, 'store'])->name('travel_orders.store');
        Route::get('/{orderId}', [TravelOrderController::class, 'show'])->name('travel_orders.show');

        Route::patch('/{orderId}/approve', [TravelOrderStatusController::class, 'approve'])->name('travel_orders.approve');
        Route::patch('/{orderId}/cancel', [TravelOrderStatusController::class, 'cancel'])->name('travel_orders.cancel');
    });
