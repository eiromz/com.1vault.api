<?php

return [
    'x-auth-signature' => env('BANK_X_AUTH_SIG'),
    'clientId' => env('BANK_CLIENT_ID'),
    'clientSecret' => env('BANK_CLIENT_SECRET'),
    'base_url' => env('BANK_BASE_URL'),
    'base_url_rest_api' => env('BANK_BASE_URL_REST_API'),
    'base_url_bills' => env('BANK_BASE_URL_BILLS'),
    'rest_api_username' => env('PROVIDUS_USERNAME'),
    'rest_api_password' => env('PROVIDUS_PASSWORD'),
    'main_merchant_account' => env('MAIN_MERCHANT_ACCOUNT')
];
