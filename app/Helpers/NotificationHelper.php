<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class NotificationHelper
{
    public static function sendMessageToWhatsApp(string $numberPhone, string $message): void
    {
        $response = Http::post(config('services.whatsapp.url') . '/message/button?key=' . config('services.whatsapp.key'), [
            'id' => $numberPhone,
            'btndata' => [
                'text' => $message,
                'buttons' => [
                    [
                        'type' => 'urlButton',
                        'title' => 'Verifikasi Sekarang',
                        'payload' => config('app.url') . '/verify',
                    ],
                ],
            ],
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to send message to WhatsApp');
        }
    }
}
