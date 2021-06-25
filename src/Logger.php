<?php

namespace App;


class Logger
{
    public static function log($module, $line)
    {
        echo '['.date('d.m.Y H:i:s').'] ['.$module.'] '.$line.PHP_EOL;
    }
}