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
     * @param string $pumpBinary
     */
    public function __construct(LoopInterface $loop, ValvesRelaysState $valvesRelaysState, \SplObjectStorage $clients, HistoryCreator $historyCreator, string $pumpBinary)
    {
        $this->loop = $loop;
        $this->valvesRelaysState = $valvesRelaysState;
        $this->clients = $clients;
        $this->pumpBinary = $pumpBinary;
        $this->historyCreator = $historyCreator;
    }

    /**
     * @param $speed
     */
    public function changePumpingSpeed($speed)
    {
        $this->broadcast(new PumpStateChanging());

        $speedPWM = $this->map($speed, 0, 10, 0, 100);

        if(!$this->valvesRelaysState->getState('main') && $speed > 0) {
          $this->valvesRelaysState->setState('main', true, function() use($speedPWM, $speed) {
              $this->setPumpSpeed($this->currentSpeed, $speedPWM, function($output) use($speed) {
                  Logger::log('PumpingStateService', $output);
                  $this->currentSpeedLiters = $speed;
              });
          });
          return;
        }

        if($this->valvesRelaysState->getState('main') && $speed == 0) {
            $this->setPumpSpeed($this->currentSpeed, $speedPWM, function($output) use($speed) {
                Logger::log('PumpingStateService', $output);
                $this->currentSpeedLiters = $speed;
            });

            $this->loop->addTimer(10, function() {
                $this->valvesRelaysState->setState('main', false);
            });

            return;
        }

        if($this->valvesRelaysState->getState('main') && $speed > 0) {
            $this->setPumpSpeed($this->currentSpeed, $speedPWM, function($output) use($speed) {
                Logger::log('PumpingStateService', $output);
                $this->currentSpeedLiters = $speed;
            });

            return;
        }
    }

    /**
     * @return int
     */
    public function getCurrentSpeedLiters(): float
    {
        return $this->currentSpeedLiters;
    }

    /**
     * @param bool $enabled
     */
    protected function changeMainValve(bool $enabled)
    {
        $this->valvesRelaysState->setState('main', $enabled);
    }

    /**
     * @param $from
     * @param $to
     * @param callable $onSuccess
     */
    private function _controlPumpSpeed($from, $to, callable  $onSuccess)
    {
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
                           $this->historyCreator->createHistoryItem(1, 1, 1, 1, $to);
                           $this->_controlPumpSpeed($from, $to, $onSuccess);
                       });
                   });
               });
            });

            return;
        }

        $this->historyCreator->createHistoryItem(null, null, null, null, $to);
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