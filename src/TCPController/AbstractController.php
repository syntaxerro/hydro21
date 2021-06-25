<?php

namespace App\TCPController;


use React\EventLoop\LoopInterface;

abstract class AbstractController
{
    /** @var LoopInterface */
    protected $loop;

    /**
     * AbstractController constructor.
     * @param LoopInterface $loop
     */
    public function __construct(LoopInterface $loop)
    {
        $this->loop = $loop;
    }

    protected function run($cmd, callable $onExit = null)
    {
        GPIO::run($this->loop, $cmd, $onExit);
    }
}