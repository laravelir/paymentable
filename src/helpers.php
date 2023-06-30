<?php

if (!function_exists('tomanToRial')) {
    function tomanToRial($amount)
    {
        return $amount * 10;
    }
}


if (!function_exists('rialToToman')) {
    function rialToToman($amount)
    {
        return $amount / 10;
    }
}
