<?php

namespace App;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Container;

abstract class AbstractController
{
    /** @var Container */
    protected $container;

    /** @var EntityManagerInterface */
    protected $em;

    /**
     * AbstractController constructor.
     * @param $container
     * @throws \Exception
     */
    public function __construct($container)
    {
        $this->container = $container;
        $this->em = $this->container->get('db.em');
    }

    /**
     * @param $status
     * @return string
     */
    protected function statusResponse($status): string
    {
        return $this->response(['status' => $status]);
    }

    /**
     * @param $data
     * @return false|string
     */
    protected function response($data): string
    {
        header('Content-type: application/json');
        return json_encode($data);
    }
}