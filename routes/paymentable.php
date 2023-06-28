<?php

// routes of laravelir/paymentable

use Illuminate\Support\Facades\Route;

Route::group([], function () {
    Route::get('/paymentable/payment', [PaymentableController::class => 'paymentable.store']);
    Route::get('/paymentable/callback', [PaymentableController::class => 'paymentable.callback']);
});
