<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'whatsapp' => [
        'url' => env('WHATSAPP_SERVICE_URL'),
        'key' => env('WHATSAPP_SERVICE_KEY'),
    ],

    'biller' => [
        'prepaid_base_url' => env('BILLER_PREPAID_BASE_URL'),
        'postpaid_base_url' => env('BILLER_POSTPAID_BASE_URL'),
        'username' => env('BILLER_USERNAME'),
        'api_key' => env('BILLER_API_KEY'),
    ],

    'flip' => [
        'base_url' => env('PAYMENT_GATEWAY_BASE_URL'),
        'secret_key' => env('PAYMENT_GATEWAY_SECRET_KEY'),
    ]

];
