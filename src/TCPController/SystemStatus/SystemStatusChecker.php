<?php

namespace App\TCPController\SystemStatus;


use App\Logger;
use React\EventLoop\LoopInterface;

class SystemStatusChecker
{
    /** @var LoopInterface */
    private $loop;

    /** @var \SplObjectStorage */
    private $clients;

    /** @var SystemStatusProviders */
    private $systemStatusProviders;

    /**
     * SystemStatusChecker constructor.
     * @param LoopInterface $loop
     * @param \SplObjectStorage $clients
     * @param SystemStatusProviders $systemStatusProviders
     */
    public function __construct(LoopInterface $loop, \SplObjectStorage $clients, SystemStatusProviders $systemStatusProviders)
    {
        $this->loop = $loop;
        $this->clients = $clients;
        $this->systemStatusProviders = $systemStatusProviders;
    }

    public function init()
    {
        Logger::log('SystemStatusChecker', 'Initialized system status checker to sending ip, datetime etc. to clients!');

        $this->loop->addPeriodicTimer(60, function() {
            $systemStatus = $this->systemStatusProviders->provideAll();

            foreach($this->clients as $client) {
                $client->send(json_encode($systemStatus));
            }
        });
    }
}