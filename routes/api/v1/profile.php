<?php

use App\Http\Controllers\API\V1\User\ProfileController;
use Illuminate\Support\Facades\Route;

Route::controller(ProfileController::class)->group(function () {
    Route::group([
        'prefix' => 'me',
        'middleware' => 'auth:sanctum',
    ], function () {
        Route::get('', 'me');
    });
});
