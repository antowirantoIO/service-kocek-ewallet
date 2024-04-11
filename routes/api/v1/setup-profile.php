<?php

use App\Http\Controllers\API\V1\User\SetupProfileController;
use Illuminate\Support\Facades\Route;

Route::controller(SetupProfileController::class)->group(function () {
    Route::group([
        'prefix' => 'setup-profile',
        'middleware' => 'auth:sanctum',
    ], function () {
        Route::post('init', 'initiateDataProfileUser');
    });
});
