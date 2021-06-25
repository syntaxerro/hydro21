<?php

namespace App\Controller;

use App\AbstractController;
use App\Entity\HistoryItem;

class HistoryController extends AbstractController
{
    public function grid(array $request)
    {
        $history = $this->em->getRepository(HistoryItem::class)->findBy([], ['id' => 'DESC'], 15, 15*$request['page']);

        return  $this->response($history);
    }
}