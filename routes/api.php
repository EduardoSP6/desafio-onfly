<?php

use Illuminate\Support\Facades\Route;

/**
 * API Routes.
 */
Route::prefix('v1')->group(function () {
    include __DIR__ . "/api/v1/auth.php";
    include __DIR__ . "/api/v1/travel_orders.php";
});
