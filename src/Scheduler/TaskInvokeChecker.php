<?php

namespace App\Scheduler;


use App\Entity\ScheduledTask;
use App\Logger;
use Doctrine\ORM\EntityRepository;
use React\EventLoop\LoopInterface;

class TaskInvokeChecker
{
    /**
     * @var LoopInterface
     */
    private $loop;

    /**
     * @var EntityRepository
     */
    private $scheduledTaskRepository;

    /**
     * @var int
     */
    private $checkingInterval;

    /**
     * @var Invoker
     */
    private $invoker;

    /**
     * TaskInvokeChecker constructor.
     * @param LoopInterface $loop
     * @param EntityRepository $scheduledTaskRepository
     * @param Invoker $invoker
     * @param int $checkingInterval
     */
    public function __construct(LoopInterface $loop, EntityRepository $scheduledTaskRepository, Invoker $invoker, int $checkingInterval)
    {
        $this->invoker = $invoker;
        $this->checkingInterval = $checkingInterval;
        $this->scheduledTaskRepository = $scheduledTaskRepository;
        $this->loop = $loop;
    }

    public function init()
    {
        Logger::log('TaskInvokeChecker', 'Initialized checker in loop. Checking every '.$this->checkingInterval.' seconds.');
        $this->loop->addPeriodicTimer($this->checkingInterval, $this->checkTaskCallback());
    }

    /**
     * @return \Closure
     */
    private function checkTaskCallback() {
        return function() {
            $now = new \DateTime();
            $currentDayOfWeek = $now->format('N')-1;
            $currentTime = $now->format('H:i');
            $tasks = $this->scheduledTaskRepository->findAll();
            /** @var ScheduledTask $task */
            foreach($tasks as $task) {
                if($task->getDayOfWeek() == $currentDayOfWeek && $task->getStartTime()->format('H:i') == $currentTime) {
                    Logger::log('TaskInvokeChecker', 'Invoked task ID #'.$task->getId());
                    $this->invoker->invokeTask($task);
                }
            }
        };
    }

}