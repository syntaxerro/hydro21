<?php

namespace App\TCPController\SystemStatus;


class IpProvider
{
    /** @var string  */
    private $network;

    const COMMAND = 'ifconfig';

    static $isDevModeEnabled = false;

    /**
     * IpProvider constructor.
     * @param string $network
     */
    public function __construct(string $network)
    {
        $this->network = $network;
    }

    /**
     * @return string
     */
    public function get()
    {
        if(self::$isDevModeEnabled) {
            return '127.0.0.1';
        }

        exec(self::COMMAND, $output);
        $ip = '#NC#';
        foreach($output as $line) {
            if(preg_match('/inet\ '.str_replace('.', '\\.', $this->network).'(\d+)/', $line, $matches)) {
                $ip = $this->network.$matches[1];
                break;
            }
        }
        return $ip;
    }
}