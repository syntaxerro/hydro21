<?php

namespace App\Controller;

use App\AbstractController;
use App\Entity\ScheduledTask;

class SchedulerController extends AbstractController
{
    public function grid()
    {
        $list = $this->em->getRepository(ScheduledTask::class)->findBy([], ['dayOfWeek' => 'ASC']);

        return $this->response($list);
    }

    public function create(array $request)
    {
        $scheduledTask = new ScheduledTask();
        $scheduledTask->setDayOfWeek($request['dayOfWeek']);
        $scheduledTask->setDuration($request['duration']);
        $scheduledTask->setStartTime(\DateTime::createFromFormat('H:i', $request['startTime']));
        $scheduledTask->setPumpSpeed($request['pumpSpeed']);
        $scheduledTask->setCh1((int)!empty($request['ch1']));
        $scheduledTask->setCh2((int)!empty($request['ch2']));
        $scheduledTask->setCh3((int)!empty($request['ch3']));
        $scheduledTask->setCh4((int)!empty($request['ch4']));

        $this->em->persist($scheduledTask);
        $this->em->flush();

        return $this->statusResponse($scheduledTask->getId());
    }

    public function remove(array $request)
    {
        $scheduledTask = $this->em->getRepository(ScheduledTask::class)->find($request['id']);
        $this->em->remove($scheduledTask);
        $this->em->flush();

        return $this->statusResponse('OK');
    }
}