<?php

namespace Laravelir\Paymentable\Contracts;

use Illuminate\Support\Facades\Redirect;

interface DriverContract
{
    public function gatewayData(): array;
    public function payment();
    public function verify();
    public function callback();
    public function paymentUrl(array $options = []): ?string;
    public function pay(array $options = []): Redirect;
    public function isSandbox();
    public function getEndpoint();
}
