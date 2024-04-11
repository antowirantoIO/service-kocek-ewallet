<?php

namespace App\Http\Controllers\API\V1\PPOB;

use App\Helpers\PaymentHelper;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class InquiryTransactionPrepaidController extends Controller
{
    public function createTransactionToBiler(Request $request)
    {
        $request->validate([
            'product_code' => 'required|string',
            'customer_number' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $product_code = $request->product_code;
        $customer_number = $request->customer_number;
        $payment_method = $request->payment_method;

        $product = Product::where('code', $product_code)->first();
        if(!$product) {
            return $this->error(
                false,
                'Product not found',
                404
            );
        }

        $payment = PaymentHelper::createPayment($product, $payment_method);

        dd($payment);
    }
}
