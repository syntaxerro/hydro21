<?php

namespace App\TCPController\PushMessages;


class SystemStatus
{
    public $controller = 'system_status';

    public $wifiSSID;

    public $ip;

    public $datetime;

    public function __construct(string $wifiSSID, string $ip, string $datetime)
    {
        $this->wifiSSID = $wifiSSID;
        $this->ip = $ip;
        $this->datetime = $datetime;
    }
}