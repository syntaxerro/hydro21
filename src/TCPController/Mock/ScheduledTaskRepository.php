<?php

namespace App\TCPController\Mock;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;

class ScheduledTaskRepository extends EntityRepository
{

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, new Mapping\ClassMetadata('ScheduledTask'));
    }

    public function findAll()
    {
        return [];
    }

}