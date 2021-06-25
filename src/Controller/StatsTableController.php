<?php

namespace App\Controller;


use App\AbstractController;
use App\Entity\StatsItem;

class StatsTableController extends AbstractController
{
    public function get(array $request)
    {
        $stats = $this->em->getRepository(StatsItem::class)->findBy([], ['date' => 'DESC'], 15, 15*$request['page']);

        return $this->response($stats);
    }
}