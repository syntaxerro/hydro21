<?php

namespace App\TCPController\PushMessages;

use App\TCPController\Current\ValvesRelaysState;

class CurrentValvesStates
{
    public $ch1;

    public $ch2;

    public $ch3;

    public $ch4;

    public $controller = 'current_valves_states';

    public static function createStates(ValvesRelaysState $valvesRelaysState)
    {
        $instance = new CurrentValvesStates();
        $instance->ch1 = $valvesRelaysState->getState('ch1');
        $instance->ch2 = $valvesRelaysState->getState('ch2');
        $instance->ch3 = $valvesRelaysState->getState('ch3');
        $instance->ch4 = $valvesRelaysState->getState('ch4');

        return $instance;
    }
}