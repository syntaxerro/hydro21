<?php

namespace App\TCPController;

use App\TCPController\Current\ValvesRelaysState;
use App\TCPController\Scheduler\TaskInvokeChecker;
use App\TCPController\SystemStatus\SystemStatusChecker;
use React\EventLoop\LoopInterface;

class Initializer
{
    private $valves = [];
    private $pump = [];

    /** @var ValvesRelaysState */
    private $valvesRelaysState;

    /** @var TaskInvokeChecker */
    private $taskInvokeChecker;

    /** @var SystemStatusChecker */
    private $systemStatusChecker;

    /** @var HistoryCreator */
    private $historyCreator;

    /** @var LoopInterface */
    private $loop;

    /**
     * Initializer constructor.
     * @param LoopInterface $loop
     * @param ValvesRelaysState $valvesRelaysState
     * @param TaskInvokeChecker $taskInvokeChecker
     * @param SystemStatusChecker $statusChecker
     * @param HistoryCreator $historyCreator
     * @param array $valves
     */
    public function __construct(LoopInterface $loop, ValvesRelaysState $valvesRelaysState, TaskInvokeChecker $taskInvokeChecker, SystemStatusChecker $statusChecker, HistoryCreator $historyCreator, array $valves, array $pump)
    {
        $this->loop = $loop;
        $this->historyCreator = $historyCreator;
        $this->valves = $valves;
        $this->pump = $pump;
        $this->valvesRelaysState = $valvesRelaysState;
        $this->taskInvokeChecker = $taskInvokeChecker;
        $this->systemStatusChecker = $statusChecker;
    }

    /**
     * Initialize hardware pins
     */
    public function init()
    {
        if($this->pump['type'] == 'rel-controlled') {
            GPIO::run($this->loop, 'echo '.$this->pump['bin'].' > '.GPIO::PATH_EXPORT, function() {
                GPIO::run($this->loop, 'echo "out" > '.sprintf(GPIO::PATH_DIRECTION, $this->pump['bin']).' && echo 1 > '.sprintf(GPIO::PATH_VALUE, $this->pump['bin']), function() {
                    $this->initValves();
                });
            });
        } else {
            $this->initValves();
        }

    }

    /**
     * Initialize valves pins
     */
    private function initValves()
    {
        foreach($this->valves as $valve) {
            GPIO::run($this->loop, 'echo '.$valve.' > '.GPIO::PATH_EXPORT, function() use($valve) {
                $this->loop->addTimer(2, function() use($valve) {
                    GPIO::run($this->loop, 'echo "out" > '.sprintf(GPIO::PATH_DIRECTION, $valve).' && echo 1 > '.sprintf(GPIO::PATH_VALUE, $valve));
                });
            });
        }

        $this->loop->addTimer(8, function() {
            $this->initSystem();
        });
    }

    /**
     * Initialize system tasks
     */
    private function initSystem()
    {
        $this->valvesRelaysState->readStates();

        $this->taskInvokeChecker->init();
        $this->systemStatusChecker->init();

        $this->historyCreator->init();
    }
}