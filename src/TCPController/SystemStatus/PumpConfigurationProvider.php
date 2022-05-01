<?php

namespace App\TCPController\SystemStatus;

class PumpConfigurationProvider
{
    private $type;

    public function __construct(array $pumpCfg)
    {
        $this->type = $pumpCfg['type'];
    }

    public function get()
    {
        return $this->type;
    }
}