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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'metalrates' => [
     'METALS_KEY' => env('METALS_KEY'),
     
    ],
    'stripe' => [
     'key' => env('STRIPE_KEY'),
     'secret' => env('STRIPE_SECRET'),
     'env' => env('STRIPE_ENV'),
    ],
    
    'fedex' => [
        'key' => env('FEDEX_KEY'),
        'secret' => env('FEDEX_SECRET'),
        'account' => env('FEDEX_ACCOUNT'),
        'url' => env('FEDEX_URL'),
    ],

];
