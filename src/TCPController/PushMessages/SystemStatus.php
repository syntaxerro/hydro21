<?php

namespace App\TCPController\PushMessages;


use App\TCPController\SystemStatus\DatetimeProvider;
use App\TCPController\SystemStatus\IpProvider;
use App\TCPController\SystemStatus\SSIDProvider;

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

    public static function fromProviders(SSIDProvider $SSIDProvider, IpProvider $ipProvider, DatetimeProvider $datetimeProvider)
    {
        return new self(
            $SSIDProvider->get(),
            $ipProvider->get(),
            $datetimeProvider->get()
        );
    }
}