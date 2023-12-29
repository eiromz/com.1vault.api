<?php

return [
    'x-auth-signature' => env('BANK_X_AUTH_SIG', 'null'),
    'clientId' => env('BANK_CLIENT_SECRET', 'null'),
    'clientSecret' => env('BANK_CLIENT_ID', 'null'),
    'base_url' => env('BANK_BASE_URL'),
];
