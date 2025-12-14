<?php

if (!function_exists('priceFormat')) {
    function priceFormat($amount)
    {
        return '₹' . number_format($amount, 2);
    }
}
