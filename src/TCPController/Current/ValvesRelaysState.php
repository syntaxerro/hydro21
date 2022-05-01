<?php

namespace App\TCPController\Current;


use App\Logger;
use App\TCPController\GPIO;
use App\TCPController\HistoryCreator;
use App\TCPController\PushMessages\CurrentValvesStates;
use React\EventLoop\LoopInterface;

class ValvesRelaysState
{
    /** @var boolean */
    private $ch1;
    /** @var boolean */
    private $ch2;
    /** @var boolean */
    private $ch3;
    /** @var boolean */
    private $ch4;

    /** @var LoopInterface */
    private $loop;

    /** @var array  */
    private $gpioConfig = [];

    /** @var \SplObjectStorage */
    private $clients;

    /**
     * @var HistoryCreator
     */
    private $historyCreator;

    /**
     * ValvesRelaysState constructor.
     * @param LoopInterface $loop
     * @param \SplObjectStorage $clients
     * @param HistoryCreator $historyCreator
     * @param array $gpioConfig
     */
    public function __construct(LoopInterface $loop, \SplObjectStorage $clients, HistoryCreator $historyCreator, array $gpioConfig)
    {
        $this->historyCreator = $historyCreator;
        $this->loop = $loop;
        $this->clients = $clients;
        $this->gpioConfig = $gpioConfig;
    }

    /**
     * Load states from GPIO header
     * @param callable $onRead
     */
    public function readStates(callable $onRead = null)
    {
        GPIO::run($this->loop, 'cat '.sprintf(GPIO::PATH_VALUE, $this->gpioConfig['ch1']), function($exitCode, $output) use($onRead) {
            $this->ch1 = !(bool)$output;

            GPIO::run($this->loop, 'cat '.sprintf(GPIO::PATH_VALUE, $this->gpioConfig['ch2']), function($exitCode, $output) use($onRead) {
                $this->ch2 = !(bool)$output;

                GPIO::run($this->loop, 'cat '.sprintf(GPIO::PATH_VALUE, $this->gpioConfig['ch3']), function($exitCode, $output) use($onRead) {
                    $this->ch3 = !(bool)$output;

                    GPIO::run($this->loop, 'cat '.sprintf(GPIO::PATH_VALUE, $this->gpioConfig['ch4']), function($exitCode, $output) use($onRead) {
                        $this->ch4 = !(bool)$output;

                        Logger::log('ValvesStatesManager', sprintf('Read states: %s %s %s %s', (int)$this->ch1, (int)$this->ch2, (int)$this->ch3, (int)$this->ch4));

                        $onRead ? $onRead() : null;
                    });
                });
            });
        });
    }

    /**
     * @param string $valve
     * @return boolean
     */
    public function getState(string $valve)
    {
        return $this->{$valve};
    }

    /**
     * @param string $valve
     * @param bool $state
     * @param callable|null $onSuccess
     */
    public function setState(string $valve, bool $state, callable $onSuccess = null)
    {
        GPIO::run($this->loop, 'echo "'.($state ? '0' : '1').'" > '.sprintf(GPIO::PATH_VALUE, $this->gpioConfig[$valve]), $onSuccess);
        $this->{$valve} = $state;
        Logger::log('ValvesStatesManager', sprintf('Read states: %s %s %s %s', (int)$this->ch1, (int)$this->ch2, (int)$this->ch3, (int)$this->ch4));
        if($valve == 'ch1') {
            $this->historyCreator->createHistoryItem((int)$state, null, null, null, null);
        } elseif($valve == 'ch2') {
            $this->historyCreator->createHistoryItem(null, (int)$state, null, null, null);
        } elseif($valve == 'ch3') {
            $this->historyCreator->createHistoryItem(null, null, (int)$state, null, null);
        } elseif($valve == 'ch4') {
            $this->historyCreator->createHistoryItem(null, null, null, (int)$state, null);
        }

        foreach($this->clients as $client) {
            $client->send(json_encode(CurrentValvesStates::createStates($this)));
        }
    }

}