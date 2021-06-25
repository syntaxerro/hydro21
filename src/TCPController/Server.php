<?php

namespace App\TCPController;


use App\Logger;
use App\TCPController\Current\PumpingState;
use App\TCPController\Current\ValvesRelaysState;
use App\TCPController\PushMessages\CurrentPumpState;
use App\TCPController\PushMessages\CurrentValvesStates;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Server implements MessageComponentInterface
{
    const REGISTER_CLIENT_CONTROLLER = 'register_client';
    const SET_VALVE_CONTROLLER = 'set_valve';
    const SET_PUMP_CONTROLLER = 'set_pump';

    /** @var ValvesRelaysState */
    private $currentValvesRelaysState;

    /** @var PumpingState */
    private $pumpingState;

    /**
     * @var ConnectionInterface[]
     */
    private $clients;

    /**
     * Server constructor.
     * @param Initializer $initializer
     * @param ValvesRelaysState $valvesRelaysState
     * @param PumpingState $pumpingState
     * @param \SplObjectStorage $clients
     */
    public function __construct(Initializer $initializer, ValvesRelaysState $valvesRelaysState, PumpingState $pumpingState, \SplObjectStorage $clients)
    {
        $initializer->init();

        $this->clients = $clients;
        $this->currentValvesRelaysState = $valvesRelaysState;
        $this->pumpingState = $pumpingState;
    }


    public function onOpen(ConnectionInterface $conn)
    {
        /** @noinspection PhpUndefinedFieldInspection */
        Logger::log('Server', 'Client connected: '.$conn->remoteAddress.' ('.$conn->resourceId.')');
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $request = @json_decode($msg, JSON_OBJECT_AS_ARRAY);
        if($request === null) {
            Logger::log('Server', 'Unexpected input: '.$msg);
            return;
        }

        switch($request['controller']) {
            case self::REGISTER_CLIENT_CONTROLLER:
                Logger::log('Server', 'Client registered from '.$from->remoteAddress.' ('.$from->resourceId.')');
                $from->send(json_encode(CurrentValvesStates::createStates($this->currentValvesRelaysState)));
                $from->send(json_encode(CurrentPumpState::createStates($this->pumpingState)));
                $this->clients->attach($from);
                break;

            case self::SET_VALVE_CONTROLLER:
                $this->currentValvesRelaysState->setState($request['valve'], $request['state']);
                break;

            case self::SET_PUMP_CONTROLLER:
                $this->pumpingState->changePumpingSpeed($request['speed']);
                break;
        }

    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        Logger::log('Server', 'Client disconnected: '.$conn->remoteAddress.' ('.$conn->resourceId.'). Client unregistered.');
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        Logger::log('Server', $e->getMessage().PHP_EOL.$e->getTraceAsString());
    }
}