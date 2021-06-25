<?php

namespace App\TCPController;


use App\Entity\HistoryItem;
use App\Logger;
use Doctrine\ORM\EntityManager;

class HistoryCreator
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * HistoryCreator constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param $ch1
     * @param $ch2
     * @param $ch3
     * @param $ch4
     * @param $pumpSpeed
     */
    public function createHistoryItem(?int $ch1, ?int $ch2, ?int $ch3, ?int $ch4, ?int $pumpSpeed)
    {
        $newHistoryItem = $this->cloneLastItem();
        $newHistoryItem->setDatetime(new \DateTime);

        if($ch1 !== null) {
            $newHistoryItem->setCh1($ch1);
        }
        if($ch2 !== null) {
            $newHistoryItem->setCh2($ch2);
        }
        if($ch3 !== null) {
            $newHistoryItem->setCh3($ch3);
        }
        if($ch4 !== null) {
            $newHistoryItem->setCh4($ch4);
        }
        if($pumpSpeed !== null) {
            $newHistoryItem->setPumpSpeed($pumpSpeed);
        }

        try {
            $this->em->persist($newHistoryItem);
            $this->em->flush();
        } catch(\Exception $e) {
            Logger::log('HistoryCreator', $e->getMessage().PHP_EOL.$e->getTraceAsString());
        }
    }

    /**
     * @return HistoryItem|object[]
     */
    private function cloneLastItem()
    {
        $lastHistoryItem = $this->em->getRepository(HistoryItem::class)->findBy([], ['id' => 'DESC'], 1);

        if(!$lastHistoryItem) {
            return new HistoryItem();
        }

        return clone $lastHistoryItem[0];
    }

}