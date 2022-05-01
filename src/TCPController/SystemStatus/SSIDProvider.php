<?php

namespace App\TCPController\SystemStatus;


class SSIDProvider
{
    const COMMAND = 'iwlist wlan0 scan | grep SSID';

    static $isDevModeEnabled = false;

    public function get()
    {
        if(self::$isDevModeEnabled) {
            return rand() % 2 ? '#NC#' : 'Sieć testowa';
        }

        exec(self::COMMAND, $output);
        if(!$output) {
            return '#NC#';
        }
        preg_match('/SSID\:\"(.*)\"/', $output[0], $matches);
        return empty($matches[1]) ? '#NC#' : $matches[1];
    }
}