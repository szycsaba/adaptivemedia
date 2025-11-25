<?php

return [
    'base_url'   => env('EXCHANGE_API_BASE_URL'),
    'api_key'    => env('EXCHANGE_API_KEY'),
    'base_currency' => env('EXCHANGE_BASE_CURRENCY'),
    'ca_cert' => base_path('ssl/cacert.pem'),
];
