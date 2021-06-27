<?php

namespace App\TCPController\SystemStatus;

use App\TCPController\PushMessages\SystemStatus;

class SystemStatusProviders
{
    /** @var IpProvider */
    private $ipProvider;

    /** @var SSIDProvider */
    private $ssidProvider;

    /** @var DatetimeProvider */
    private $datetimeProvider;

    /**
     * AllProviders constructor.
     * @param IpProvider $ipProvider
     * @param SSIDProvider $ssidProvider
     * @param DatetimeProvider $datetimeProvider
     */
    public function __construct(IpProvider $ipProvider, SSIDProvider $ssidProvider, DatetimeProvider $datetimeProvider)
    {
        $this->ipProvider = $ipProvider;
        $this->ssidProvider = $ssidProvider;
        $this->datetimeProvider = $datetimeProvider;
    }

    /**
     * @return SystemStatus
     */
    public function provideAll()
    {
        return SystemStatus::fromProviders($this->ssidProvider, $this->ipProvider, $this->datetimeProvider);
    }
}