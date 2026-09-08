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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
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

    'clickup' => [
        'token' => env('CLICK_UP_API_TOKEN'),
        'folder_id' => env('CLICK_UP_FOLDER_ID'),
    ],

    'supabase' => [
        'url' => env('SUPABASE_URL'),
        'service_key' => env('SUPABASE_SERVICE_KEY'),
    ],

    'bank' => [
        'banco' => env('BANK_NAME', 'BBVA'),
        'beneficiario' => env('BANK_BENEFICIARY', 'Miguel Ibarra (Propietario)'),
        'clabe' => env('BANK_CLABE', '012180015609353103'),
        'cuenta' => env('BANK_ACCOUNT', '1560935310'),
        'rfc' => env('BANK_RFC', null),
    ],
];
