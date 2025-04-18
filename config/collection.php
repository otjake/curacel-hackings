<?php

return [
    'sdk' => [
        /*
        |--------------------------------------------------------------------------
        | Url to the sdk javascript file that can be embedded on a webpage
        |--------------------------------------------------------------------------
        */
        'script' => env('COLLECTION_SDK_SCRIPT'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Provider that should be used by default
    |--------------------------------------------------------------------------
    | Options: Paystack, Flutterwave
    */
    'default_provider' => env('COLLECTION_DEFAULT_PROVIDER', 'Paystack'),

    /*
    |--------------------------------------------------------------------------
    | How provider webhook should be handled. This can also be set per provider
    |--------------------------------------------------------------------------
    | Options: verify, assert
    |
    | verify: Go back to the provider to verify the status of the transaction.
    | assert: Determine the status of the transaction from the webhook payload.
    */
    'webhook_action' => env('COLLECTION_PROVIDER_WEBHOOK_ACTION', 'assert'),
];
