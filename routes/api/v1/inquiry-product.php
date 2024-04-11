<?php

use App\Http\Controllers\API\V1\PPOB\InquiryProductPrepaidController;
use Illuminate\Support\Facades\Route;

Route::controller(InquiryProductPrepaidController::class)->group(function () {
    Route::group([
        'prefix' => 'inquiry/product',
        'middleware' => 'auth:sanctum',
    ], function () {
        Route::get('pulsa-data', 'getProductPulsaData');

        Route::get('pln-prepaid', 'getProductPlnPrepaid');
        Route::get('pln-prepaid-customer', 'getCustomerPlnPrepaid');

        Route::get('emoney', 'getProductEmoney');
        Route::get('ematerai', 'getProductEmaterai');

        Route::get('type-game-product', 'getProductTypeGame');
        Route::get('game', 'getProductGameByType');

        Route::get('type-voucher-product', 'getProductTypeVoucher');
        Route::get('voucher', 'getProductVoucherByType');
    });
});
