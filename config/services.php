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

    'fusionauth' => [
        'client_id' => env('FUSIONAUTH_CLIENT_ID'),
        'client_secret' => env('FUSIONAUTH_CLIENT_SECRET'),
        'redirect' => env('FUSIONAUTH_REDIRECT_URI'),
        'base_url' => env('FUSIONAUTH_BASE_URL'),
        'tenant_id' => env('FUSIONAUTH_TENANT_ID'),
    ],

    'anchor' => [
        'api_key' => env('ANCHOR_API_KEY'),
        'base_url' => env('ANCHOR_BASE_URL'),
        'webhook_secret' => env('ANCHOR_WEBHOOK_SECRET'),
        'customer_account_type' => env('ANCHOR_CUSTOMER_ACCOUNT_TYPE', 'SubAccount'),
        'master_account_id' => env('ANCHOR_MASTER_ACCOUNT_ID'),
    ],

    'paystack' => [
        'base_url' => env('PAYSTACK_BASE_URL'),
        'public_key' => env('PAYSTACK_PUBLIC_KEY'),
        'secret_key' => env('PAYSTACK_SECRET_KEY'),
        'webhook_action' => env('PAYSTACK_WEBHOOK_ACTION', 'assert'),
    ],

    'flutterwave' => [
        'base_url' => env('FLUTTERWAVE_BASE_URL'),
        'public_key' => env('FLUTTERWAVE_PUBLIC_KEY'),
        'secret_key' => env('FLUTTERWAVE_SECRET_KEY'),
        'secret_hash' => env('FLUTTERWAVE_SECRET_HASH'),
        'webhook_action' => env('FLUTTERWAVE_WEBHOOK_ACTION', 'assert'),
    ],

    'health-api' => [
        'base_url' => env('HEALTH_API_BASE_URL'),
    ],

    'sage' => [
        'client_id' => env('SAGE_CLIENT_ID'),
        'client_secret' => env('SAGE_CLIENT_SECRET'),
        'redirect' => env('SAGE_REDIRECT_URI'),
    ],

    'slack' => [
        'stakeholder_alerts_webhook_url' => env('SLACK_STAKEHOLDER_ALERTS_WEBHOOK_URL'),
        // 'notifications' => [
        //     'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
        //     'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        // ],
    ],

    'convoy' => [
        'api_key' => env('CONVOY_API_KEY'),
        'base_url' => env('CONVOY_BASE_URL', 'https://curacel.getconvoy.cloud'),
        'project_id' => env('CONVOY_PROJECT_ID'),
        'endpoints_owner_id' => env('CONVOY_ENDPOINTS_OWNER_ID'),
    ],

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
    ],
];
