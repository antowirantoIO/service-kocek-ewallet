<?php

use App\Http\Controllers\API\V1\Auth\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthenticationController::class)->group(function () {
    Route::group([
        'prefix' => 'auth',
    ], function () {
        Route::post('request-grant-token', 'requestGrantToken');
        Route::post('verify-and-grant-token', 'verifyAndGrandToken');
    });
});
