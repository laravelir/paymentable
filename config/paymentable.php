<?php

// config file for laravelir/paymentable
return [
    'driver' => env('PAYMENTABLE_DRIVER', 'zarinpal'),

    'drivers' => [

        'zarinpal' => [
            'sandbox' => env('', false),
            'merchant_id' => env(''),
        ],

        'idpay' => [
            'sandbox' => env('', false),
            'api_key' => env(''),
        ],

        'yekpay' => [],
    ],

    'description' => '',
    'callback' => '',
];
