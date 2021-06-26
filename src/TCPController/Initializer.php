<?php

namespace App\TCPController;


use App\Scheduler\TaskInvokeChecker;
use App\TCPController\Current\ValvesRelaysState;
use React\EventLoop\LoopInterface;

class Initializer
{
    private $valves = [];

    /** @var ValvesRelaysState */
    private $valvesRelaysState;

    /** @var TaskInvokeChecker */
    private $taskInvokeChecker;

    /** @var HistoryCreator */
    private $historyCreator;

    /** @var LoopInterface */
    private $loop;

    /**
     * Initializer constructor.
     * @param LoopInterface $loop
     * @param ValvesRelaysState $valvesRelaysState
     * @param TaskInvokeChecker $taskInvokeChecker
     * @param HistoryCreator $historyCreator
     * @param array $valves
     */
    public function __construct(LoopInterface $loop, ValvesRelaysState $valvesRelaysState, TaskInvokeChecker $taskInvokeChecker, HistoryCreator $historyCreator, array $valves)
    {
        $this->loop = $loop;
        $this->historyCreator = $historyCreator;
        $this->valves = $valves;
        $this->valvesRelaysState = $valvesRelaysState;
        $this->taskInvokeChecker = $taskInvokeChecker;
    }

    /**
     * Initialize hardware pins
     */
    public function init()
    {
        $i=0;
        $j=0;
        foreach($this->valves as $valve) {
            GPIO::run($this->loop, 'echo '.$valve.' > '.GPIO::PATH_EXPORT, function() use(&$i, &$j) {
                if($i++ >= 4) {
                    $this->loop->addTimer(2, function() use(&$j) {
                        foreach($this->valves as $valve) {
                            GPIO::run($this->loop, 'echo "out" > '.sprintf(GPIO::PATH_DIRECTION, $valve).' && echo 1 > '.sprintf(GPIO::PATH_VALUE, $valve), function() use(&$j) {
                                if($j++ >= 4) {
                                    $this->loop->addTimer(1, function() {
                                        $this->valvesRelaysState->readStates();
                                    });
                                    $this->loop->addTimer(5, function() {
                                        $this->taskInvokeChecker->init();
                                    });
                                    $this->historyCreator->createHistoryItem(0, 0, 0, 0, 0);
                                }
                            });
                        }
                    });
                }
            });
        }

    }
}