<?php

namespace App;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\Container;

abstract class AbstractCommand extends Command
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @param $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }
}