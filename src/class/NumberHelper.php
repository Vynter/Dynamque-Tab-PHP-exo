<?php

namespace App;

class NumberHelper
{


    /**
     * @param mixed $price 
     * @param string $currency
     * 
     * @return string display the price with this format ********,00 € as default.
     */
    public static function Price($price, $currency = '€'): string
    {

        return number_format($price, 2, ',', ' ') . ' ' . $currency;
    }
}