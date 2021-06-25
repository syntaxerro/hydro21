<?php

namespace App\Command;

use App\AbstractCommand;
use App\Entity\HistoryItem;
use App\Entity\StatsItem;
use App\Stats\LastChecking;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CalculateStatsCommand extends AbstractCommand
{
    protected static $defaultName = 'app:calculate-stats';

    /** @var EntityManager */
    private $em;

    /** @var OutputInterface */
    private $output;

    /** @var float */
    private $efficiency;

    /** @var float */
    private $minimumLiters;

    protected function configure(): void
    {
        $this
            ->setDescription('Przeliczanie statystyk na podstawie historii');
    }

    private function init(OutputInterface $output)
    {
        $this->output = $output;
        $this->em = $this->container->get('db.em');
        $this->efficiency = $this->container->getParameter('stats')['efficiency'];
        $this->minimumLiters = $this->container->getParameter('stats')['minimum_liters'];
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->init($output);

        $history = $this->getHistoryToProcess();
        if(!$history) {
            $output->writeln('Brak nowych wpisów w historii do przeliczenia statystyk. Spróbuj ponownie później.');
            return 55;
        }

        if($history[count($history)-1]->getPumpSpeed()) {
            $output->writeln('Nie można przeliczyć statystyk ponieważ właśnie trwa nawadnianie. Spróbuj ponownie później.');
            return 56;
        }


        $this->processHistory($history);
        $this->em->flush();
        LastChecking::set(new \DateTime);

        return 0;
    }

    private function processHistory(array $history)
    {
        foreach($history as $i => $item) {
            if(!$item->getPumpSpeed()) {
                continue;
            }

            $litersPerMinutePerChannel = ($item->getPumpSpeed()/10/$item->getAmountOfEnabledChannels())*$this->efficiency;
            $litersPerSecondPerChannel = $litersPerMinutePerChannel/60;

            $nextItem = $history[$i+1];
            $diff = $item->getDatetime()->diff($nextItem->getDatetime());
            $amountOfSeconds = $diff->i*60;
            $amountOfSeconds += $diff->s;

            $litersPerChannel = $litersPerSecondPerChannel*$amountOfSeconds;

            if(!$litersPerChannel || $litersPerChannel < $this->minimumLiters) {
                continue;
            }


            $this->createStats(clone $item->getDatetime(), $amountOfSeconds, $item, $litersPerChannel);
            $this->output->writeln('#'.$item->getId().' <-> '.$nextItem->getId());
            $this->output->writeln($item->getDatetime()->format('Y-m-d H:i:s').' <-> '.$nextItem->getDatetime()->format('Y-m-d H:i:s'));
            $this->output->writeln('Seconds: '.$amountOfSeconds);
            $this->output->writeln('Liters per channel: '.$litersPerChannel);
            $this->output->write(PHP_EOL);
        }
    }

    /**
     * @param \DateTime $start
     * @param int $seconds
     * @param HistoryItem $historyItem
     * @param float $perChannelLiters
     * @throws \Doctrine\ORM\ORMException
     */
    private function createStats(\DateTime $start, int $seconds, HistoryItem $historyItem, float $perChannelLiters)
    {
        $statsItem = new StatsItem();
        $statsItem->setDate($start);
        $statsItem->setSeconds($seconds);
        if($historyItem->getCh1()) {
            $statsItem->setCh1AmountOfLiters($perChannelLiters);
        }
        if($historyItem->getCh2()) {
            $statsItem->setCh2AmountOfLiters($perChannelLiters);
        }
        if($historyItem->getCh3()) {
            $statsItem->setCh3AmountOfLiters($perChannelLiters);
        }
        if($historyItem->getCh4()) {
            $statsItem->setCh4AmountOfLiters($perChannelLiters);
        }

        $this->em->persist($statsItem);
    }


    /**
     * @return HistoryItem[]
     */
    private function getHistoryToProcess()
    {
        return $this->em->getRepository(HistoryItem::class)
            ->createQueryBuilder('h')
            ->where('h.datetime > :datetime')
            ->setParameter('datetime', LastChecking::get()->format('Y-m-d H:i:s'))
            ->orderBy('h.datetime', 'ASC')
            ->getQuery()->getResult();
    }
}