<?php

namespace App\TCPController\PushMessages;

use App\TCPController\Current\ValvesRelaysState;

class CurrentStates
{
    public $ch1;

    public $ch2;

    public $ch3;

    public $ch4;

    public $main;

    public $pumpPower;

    public $pumpEnabled;

    public $controller = 'current_states';

    public static function createStates(ValvesRelaysState $valvesRelaysState)
    {
        $instance = new CurrentStates();
        $instance->ch1 = $valvesRelaysState->getState('ch1');
        $instance->ch2 = $valvesRelaysState->getState('ch2');
        $instance->ch3 = $valvesRelaysState->getState('ch3');
        $instance->ch4 = $valvesRelaysState->getState('ch4');
        $instance->main = $valvesRelaysState->getState('main');

        return $instance;
    }
}