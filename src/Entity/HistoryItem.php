<?php

namespace App\Entity;

use App\Locale\DaysOfWeek;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class HistoryItem
 * @package App\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="history_item")
 */
class HistoryItem implements \JsonSerializable
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $datetime;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatetime(): ?\DateTime
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTime $datetime): self
    {
        $this->datetime = $datetime;
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

    /**
     * @return int
     */
    public function getAmountOfEnabledChannels()
    {
        $amount = 0;
        if($this->ch1) {
            $amount++;
        }
        if($this->ch2) {
            $amount++;
        }
        if($this->ch3) {
            $amount++;
        }
        if($this->ch4) {
            $amount++;
        }

        return $amount;
    }

    public function __clone()
    {
        $this->id = null;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'datetime' => DaysOfWeek::NAMES[$this->datetime->format('N')-1].' '.$this->datetime->format('d.m.Y H:i:s'),
            'ch1' => $this->ch1 ? 'ON' : 'OFF',
            'ch2' => $this->ch2 ? 'ON' : 'OFF',
            'ch3' => $this->ch3 ? 'ON' : 'OFF',
            'ch4' => $this->ch4 ? 'ON' : 'OFF',
            'pumpSpeed' => $this->pumpSpeed ? ($this->pumpSpeed/10).' litr/min' : 'OFF'
        ];
    }
}