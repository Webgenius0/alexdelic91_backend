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

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],



    'firebase' => [
        'credentials_file' => storage_path('app/instant-business-2da86-c6675edb8bd4.json'),
        'customer_credentials_file' => storage_path('app/instant-help-7995f-f898b72fb87f.json')
    ],


    'google' => [
        'client_id' => env('GOOGLE_LOGIN_CLIENT_ID'),
        'client_secret' => env('GOOGLE_LOGIN_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_LOGIN_REDIRECT_URI'),
    ],

    'apple' => [
        'app1' => [
            'client_id' => env('APPLE1_CLIENT_ID'),
            'key_id' => env('APPLE1_KEY_ID'),
            'private_key' => storage_path('apple_private_key1.pem'),
        ],
        'app2' => [
            'client_id' => env('APPLE2_CLIENT_ID'),
            'key_id' => env('APPLE2_KEY_ID'),
            'private_key' => storage_path('apple_private_key2.pem'),
        ],
        'team_id' => env('APPLE_TEAM_ID'),
        'redirect' => env('APPLE_REDIRECT_URI'),
    ],
];
