<?php

namespace Laravelir\Paymentable\Drivers;

use Laravelir\Paymentable\Contracts\DriverContract;

abstract class Driver implements DriverContract
{
    public function payment()
    {
        //
    }

    public function isSandbox(): bool
    {
        return true;
    }
}
