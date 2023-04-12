<?php

// config file for laravelir/paymentable
return [
    'driver' => env('PAYMENTABLE_DRIVER', 'zarinpal'),

    'drivers' => [
        'zarinpal' => [],
        'yekpay' => [],
        'idpay' => [],
    ],
];
