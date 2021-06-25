<?php


namespace App\Controller;


use App\AbstractController;

class IpController extends AbstractController
{
    const NETWORK = '192.168.1.';

    public function get()
    {
        exec('ifconfig', $output);
        $ip = null;
        foreach($output as $line) {
            if(preg_match('/inet\ '.str_replace('.', '\\.', self::NETWORK).'(\d+)/', $line, $matches)) {
                $ip = self::NETWORK.$matches[1];
                break;
            }
        }
        return $this->response(['ip' => $ip]);
    }
}