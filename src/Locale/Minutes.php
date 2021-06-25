<?php

namespace App\Locale;

class Minutes
{
    public static function getLabel($value)
    {
        $strValue = (string)$value;
        $lastChar = substr($strValue, -1);

        if($value == 1) return 'minuta';

        if($lastChar == 2 || $lastChar == 3 || $lastChar == 4) return 'minuty';

        return 'minut';
    }
}