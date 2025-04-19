<?php

return [
    'credentials' => [
        'key' => env('TEXTTRACT_ACCESS_KEY_ID'),
        'secret' => env('TEXTTRACT_SECRET_ACCESS_KEY'),
    ],
    'region' => env('TEXTTRACT_DEFAULT_REGION', 'us-east-1'),
    'version' => 'latest',
]; 