<?php

return [
    'x-auth-signature' => env('BANK_X_AUTH_SIG', 'null'),
    'clientId' => env('BANK_CLIENT_SECRET', 'null'),
    'clientSecret' => env('BANK_CLIENT_ID', 'null'),
    'base_url' => env('BANK_BASE_URL'),
    'base_url_rest_api' => env('BANK_BASE_URL_REST_API'),
    'rest_api_username' => env('PROVIDUS_USERNAME'),
    'rest_api_password' => env('PROVIDUS_PASSWORD'),
];
