<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => 'Service Kocek API (V1)',
    ]);
});

Route::group([
    'prefix' => 'v1'
], function () {
    require_once __DIR__ . '/api/v1/auth.php';
    require_once __DIR__ . '/api/v1/setup-profile.php';
    require_once __DIR__ . '/api/v1/profile.php';

    require_once __DIR__ . '/api/v1/inquiry-product.php';
    require_once __DIR__ . '/api/v1/transaction-biller-prepaid.php';
});
