<?php

namespace App\TCPController;


use App\Entity\HistoryItem;
use App\Logger;
use App\TCPController\Current\PumpingState;
use Doctrine\ORM\EntityManagerInterface;

class HistoryCreator
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    private $relays = [];

    private $pumpSpeed = 0;

    /**
     * HistoryCreator constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function saveRelaysState(int ...$relays)
    {
        $this->relays = $relays;
        if($this->pumpSpeed == 0) {
            return;
        }

        $this->createHistoryItem();
    }

    public function savePumpSpeed(int $pumpSpeed)
    {
        $this->pumpSpeed = $pumpSpeed;
        $this->createHistoryItem();
    }

    public function init()
    {
        $this->saveRelaysState(0, 0, 0, 0);
        $this->savePumpSpeed(0);
    }


    public function createHistoryItem()
    {
        $newHistoryItem = new HistoryItem();

        $newHistoryItem->setDatetime(new \DateTime);

        $newHistoryItem->setCh1($this->relays[0]);
        $newHistoryItem->setCh2($this->relays[1]);
        $newHistoryItem->setCh3($this->relays[2]);
        $newHistoryItem->setCh4($this->relays[3]);

        $newHistoryItem->setPumpSpeed($this->pumpSpeed);

        try {
            $this->em->persist($newHistoryItem);
            $this->em->flush();
        } catch(\Exception $e) {
            Logger::log('HistoryCreator', $e->getMessage().PHP_EOL.$e->getTraceAsString());
        }
    }
}