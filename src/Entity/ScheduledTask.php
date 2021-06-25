<?php

namespace App\Entity;


use App\Locale\DaysOfWeek;
use App\Locale\Minutes;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ScheduledWeekDay
 * @package App\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="scheduled_task")
 */
class ScheduledTask implements \JsonSerializable
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $dayOfWeek;

    /**
     * @var \DateTime
     * @ORM\Column(type="time")
     */
    private $startTime;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $ch1;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $ch2;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $ch3;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $ch4;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $pumpSpeed;

    public function getDayOfWeek(): int
    {
        return $this->dayOfWeek;
    }

    public function setDayOfWeek(int $dayOfWeek): self
    {
        $this->dayOfWeek = $dayOfWeek;
        return $this;
    }

    public function getStartTime(): \DateTime
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTime $startTime): self
    {
        $this->startTime = $startTime;
        return $this;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;
        return $this;
    }

    public function getCh1(): int
    {
        return $this->ch1;
    }

    public function setCh1(int $ch1): self
    {
        $this->ch1 = $ch1;
        return $this;
    }

    public function getCh2(): int
    {
        return $this->ch2;
    }

    public function setCh2(int $ch2): self
    {
        $this->ch2 = $ch2;
        return $this;
    }

    public function getCh3(): int
    {
        return $this->ch3;
    }

    public function setCh3(int $ch3): self
    {
        $this->ch3 = $ch3;
        return $this;
    }

    public function getCh4(): int
    {
        return $this->ch4;
    }

    public function setCh4(int $ch4): self
    {
        $this->ch4 = $ch4;
        return $this;
    }

    public function getPumpSpeed(): int
    {
        return $this->pumpSpeed;
    }

    public function setPumpSpeed(int $pumpSpeed): self
    {
        $this->pumpSpeed = $pumpSpeed;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'dayOfWeek' => DaysOfWeek::NAMES[$this->dayOfWeek],
            'startTime' => $this->startTime->format('H:i'),
            'duration' => $this->duration.' '.Minutes::getLabel($this->duration),
            'ch1' => $this->ch1,
            'ch2' => $this->ch2,
            'ch3' => $this->ch3,
            'ch4' => $this->ch4,
            'pumpSpeed' => $this->pumpSpeed.' litrów / minutę'
        ];
    }
}