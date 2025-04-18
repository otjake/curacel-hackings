<?php

return [
    'payout' => [
        'allow_automated_reversals' => env('PAYOUT_ALLOW_AUTOMATED_REVERSALS', true),
        'auto_retry_enabled' => env('PAYOUT_AUTO_RETRY_ENABLED', true),
    ],
];
