<?php

namespace App\TCPController\PushMessages;

use App\TCPController\Current\PumpingState;

class CurrentPumpState
{
    public $speed;

    public $controller = 'current_pump_state';

    public static function createStates(PumpingState $pumpingState)
    {
        $instance = new CurrentPumpState();
        $instance->speed = $pumpingState->getCurrentSpeedLiters();

        return $instance;
    }
}