<?php

use Illuminate\Support\Facades\Route;

/**
 * API Routes.
 */

Route::get('status', function () {
    return [
        'DATE' => date("y-m-d H:i:s"),
        'APP_NAME' => config('app.name'),
        'APP_ENV' => config('app.env'),
        'APP_URL' => config('app.url'),
    ];
});

Route::prefix('v1')->group(function () {
    include __DIR__ . "/api/v1/auth.php";
    include __DIR__ . "/api/v1/travel_orders.php";
});
