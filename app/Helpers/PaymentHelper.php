<?php

namespace App\Helpers;

use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PaymentHelper
{

    public static function getPaymentMethods()
    {
        $secret_key = config('services.flip.secret_key');
        $encoded_auth = base64_encode($secret_key . ':');

        $response = Http::withHeaders(
            [
                'Authorization' => 'Basic ' . $encoded_auth
            ]
        )
            ->get(config('services.flip.base_url') . '/general/banks');

        return $response->json();
    }

    public static function createPayment(
        Product $product,
        string $payment_method
    )
    {
        $expired_date = now()->addDays()->format('Y-m-d H:i');

        $secret_key = config('services.flip.secret_key');
        $encoded_auth = base64_encode($secret_key . ':');

        $response = Http::withHeaders(
            [
                'Authorization' => 'Basic ' . $encoded_auth
            ]
        )
            ->post(config('services.flip.base_url') . '/pwf/bill', [
                'title' => "Pembayaran Untuk " . strtoupper($product->category->name) . " " . strtoupper($product->type->name) . " " . number_format((int)$product->denomination),
                'type' => 'SINGLE',
                'amount' => $product->price_sell,
                'expired_date' =>$expired_date,
                'step' => "3",
                'sender_name' => Auth::user()->name,
                'sender_email' => Auth::user()->email,
                'sender_phone_number' => Auth::user()->country_code . Auth::user()->number_phone,
                'sender_bank' => $payment_method,
                'sender_bank_type' => 'virtual_account'
            ]);


        Payment::create([
            'user_id' => Auth::id(),
            'reference' => $response['bill_payment']['id'],
            'payment_method' => $payment_method,
            'payment_method_type' => 'virtual_account',
            'account_number' => $response['bill_payment']['receiver_bank_account']['account_number'] ?? null,
            'qr_code_data' => $response['bill_payment']['receiver_bank_account']['qr_code_data'] ?? null,
            'payment_url' => $response['payment_url'],
            'amount' => $response['amount'],
            'fee' => $response['data']['fee'],
            'expired_date' => $expired_date,
            'status' => 'PENDING'
        ]);

        return $response->json();
    }
}

