<?php

namespace App\TCPController\Current;


use App\Logger;
use App\TCPController\GPIO;
use App\TCPController\HistoryCreator;
use App\TCPController\PushMessages\CurrentPumpState;
use App\TCPController\PushMessages\PumpStateChanging;
use React\EventLoop\LoopInterface;

class PumpingState
{
    /** @var LoopInterface */
    private $loop;

    /** @var ValvesRelaysState */
    private $valvesRelaysState;

    /** @var \SplObjectStorage */
    private $clients;

    /** @var string */
    private $pumpBinary;

    /** @var string */
    private $pumpType;

    /** @var int */
    private $currentSpeed = 0;

    /** @var int */
    private $currentSpeedLiters = 0;

    /** @var HistoryCreator */
    private $historyCreator;

    /**
     * PumpingState constructor.
     * @param LoopInterface $loop
     * @param ValvesRelaysState $valvesRelaysState
     * @param \SplObjectStorage $clients
     * @param HistoryCreator $historyCreator
     * @param array $pumpCfg
     */
    public function __construct(LoopInterface $loop, ValvesRelaysState $valvesRelaysState, \SplObjectStorage $clients, HistoryCreator $historyCreator, array $pumpCfg)
    {
        $this->loop = $loop;
        $this->valvesRelaysState = $valvesRelaysState;
        $this->clients = $clients;
        $this->pumpBinary = $pumpCfg['bin'];
        $this->pumpType = $pumpCfg['type'];
        $this->historyCreator = $historyCreator;
    }

    /**
     * @param $speed
     */
    public function changePumpingSpeed($speed)
    {
        $this->broadcast(new PumpStateChanging());

        $speedPWM = $this->map($speed, 0, 10, 0, 100);

        if($speed > 0) {
            $this->setPumpSpeed($this->currentSpeed, $speedPWM, function($output) use($speed) {
                Logger::log('PumpingStateService', $output);
                $this->currentSpeedLiters = $speed;
            });
          return;
        }

        if($speed == 0) {
            $this->setPumpSpeed($this->currentSpeed, $speedPWM, function($output) use($speed) {
                Logger::log('PumpingStateService', $output);
                $this->currentSpeedLiters = $speed;
            });
            return;
        }
    }

    /**
     * @return float
     */
    public function getCurrentSpeedLiters(): float
    {
        return $this->currentSpeedLiters;
    }

    /**
     * @param $from
     * @param $to
     * @param callable $onSuccess
     */
    private function _controlPumpSpeed($from, $to, callable  $onSuccess)
    {
        if($this->pumpType == 'dcm-controlled') {
            GPIO::run(
                $this->loop,
                $this->pumpBinary.' '.$from.' '.$to,
                function($exitCode, $output) use($onSuccess, $to) {
                    if($exitCode == 0) {
                        $this->currentSpeed = $to;
                        $onSuccess($output);
                        $this->broadcast(CurrentPumpState::createStates($this));
                    } else {
                        Logger::log('PumpingStateService', 'Cannot set pump speed! Exit code: '.$exitCode);
                    }
                }
            );
            return;
        }

        if($this->pumpType == 'rel-controlled') {
            $this->currentSpeed = $to;
            GPIO::run($this->loop, 'echo "'.($to ? '0' : '1').'" > '.sprintf(GPIO::PATH_VALUE, $this->pumpBinary), $onSuccess);
            $this->loop->addTimer(2.5, function() {
                $this->broadcast(CurrentPumpState::createStates($this));
            });
        }
    }

    /**
     * @param $from
     * @param $to
     * @param callable $onSuccess
     */
    protected function setPumpSpeed($from, $to, callable $onSuccess)
    {
        if($to > 0 && !$this->valvesRelaysState->getState('ch1') && !$this->valvesRelaysState->getState('ch2') && !$this->valvesRelaysState->getState('ch3') && !$this->valvesRelaysState->getState('ch4')) {
            $this->valvesRelaysState->setState('ch1', true, function() use($from, $to, $onSuccess) {
               $this->valvesRelaysState->setState('ch2', true, function() use($from, $to, $onSuccess) {
                   $this->valvesRelaysState->setState('ch3', true, function() use($from, $to, $onSuccess) {
                       $this->valvesRelaysState->setState('ch4', true, function() use($from, $to, $onSuccess) {
                           $this->_controlPumpSpeed($from, $to, $onSuccess);
                       });
                   });
               });
            });

            return;
        }

        $this->historyCreator->savePumpSpeed($to);
        $this->_controlPumpSpeed($from, $to, $onSuccess);
    }

    /**
     * @param $message
     */
    private function broadcast($message)
    {
        foreach($this->clients as $client) {
            $client->send(json_encode($message));
        }
    }

    /**
     * @param $value
     * @param $fromLow
     * @param $fromHigh
     * @param $toLow
     * @param $toHigh
     * @return float|int
     */
    private function map($value, $fromLow, $fromHigh, $toLow, $toHigh) {
        $fromRange = $fromHigh - $fromLow;
        $toRange = $toHigh - $toLow;
        $scaleFactor = $toRange / $fromRange;

        // Re-zero the value within the from range
        $tmpValue = $value - $fromLow;
        // Rescale the value to the to range
        $tmpValue *= $scaleFactor;
        // Re-zero back to the to range
        return $tmpValue + $toLow;
    }
}