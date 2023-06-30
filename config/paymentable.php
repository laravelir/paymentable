<?php

// config file for laravelir/paymentable
return [
    'driver' => env('PAYMENTABLE_DRIVER', 'zarinpal'),

    'drivers' => [

        'zarinpal' => [
            'sandbox' => env('ZARINPAL_SANDBOX', false),
            'merchant_id' => env('ZARINPAL_MERCHANT_ID'),
        ],

        'idpay' => [
            'sandbox' => env('IDPAY_SANDBOX', false),
            'api_key' => env('IDPAY_API_KEY'),
        ],

        'yekpay' => [],

        'zibal' => [],
    ],

    'default_description' => '',

    'callback_url' => '',
];
