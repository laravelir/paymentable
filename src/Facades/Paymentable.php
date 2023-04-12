<?php

namespace Laravelir\Paymentable\Facades;

use Illuminate\Support\Facades\Facade;

class Paymentable extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'paymentable';
    }
}
