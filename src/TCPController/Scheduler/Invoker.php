<?php

namespace App\TCPController\Scheduler;


use App\Entity\ScheduledTask;
use App\TCPController\Current\PumpingState;
use App\TCPController\Current\ValvesRelaysState;
use React\EventLoop\LoopInterface;

class Invoker
{
    /** @var LoopInterface */
    private $loop;

    /**
     * @var PumpingState
     */
    private $pumpingState;

    /**
     * @var ValvesRelaysState
     */
    private $valvesRelaysState;

    /**
     * Invoker constructor.
     * @param LoopInterface $loop
     * @param PumpingState $pumpingState
     * @param ValvesRelaysState $valvesRelaysState
     */
    public function __construct(LoopInterface $loop, PumpingState $pumpingState, ValvesRelaysState $valvesRelaysState)
    {
        $this->loop = $loop;
        $this->pumpingState = $pumpingState;
        $this->valvesRelaysState = $valvesRelaysState;
    }

    /**
     * @param ScheduledTask $task
     */
    public function invokeTask(ScheduledTask $task)
    {
        $this->valvesRelaysState->setState('ch1', (bool)$task->getCh1());
        $this->valvesRelaysState->setState('ch2', (bool)$task->getCh2());
        $this->valvesRelaysState->setState('ch3', (bool)$task->getCh3());
        $this->valvesRelaysState->setState('ch4', (bool)$task->getCh4());

        $this->pumpingState->changePumpingSpeed($task->getPumpSpeed());

        $this->loop->addTimer($task->getDuration()*60, function() {
           $this->pumpingState->changePumpingSpeed(0);
           $this->loop->addTimer(15, function() {
               $this->valvesRelaysState->setState('ch1', false);
               $this->valvesRelaysState->setState('ch2', false);
               $this->valvesRelaysState->setState('ch3', false);
               $this->valvesRelaysState->setState('ch4', false);
           });

        });
    }
}