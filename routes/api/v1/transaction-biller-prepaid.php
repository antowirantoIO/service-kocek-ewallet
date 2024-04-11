<?php

use App\Http\Controllers\API\V1\PPOB\InquiryTransactionPrepaidController;
use Illuminate\Support\Facades\Route;

Route::controller(InquiryTransactionPrepaidController::class)->group(function () {
    Route::group([
        'prefix' => 'inquiry/prepaid',
        'middleware' => 'auth:sanctum',
    ], function () {
        Route::post('transaction', 'createTransactionToBiler');
    });
});
