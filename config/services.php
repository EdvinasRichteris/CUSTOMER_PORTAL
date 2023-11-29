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
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'salesforce' => [
        'client_id' => env('3MVG9cHH2bfKACZZMIHnDol25ZG6rAU_xZm0RYkrX7HyUGJ8qtwDS.NKjed10YMeMiSHqRMjWAEHzjbEsD07E'),
        'client_secret' => env('B0617EAB724C09A89DBAA3B823073382C6EA804A0B1F820686377E242A8B1A63'),
        'redirect' => env('https://e706-84-15-186-7.ngrok-free.app/oauth/callback'),
    ],

];
