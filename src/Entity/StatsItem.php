<?php

namespace App\Entity;

use App\Locale\DaysOfWeek;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class StatsItem
 * @package App\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="stats_item")
 */
class StatsItem implements \JsonSerializable
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
     *
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $seconds;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $ch1AmountOfLiters = 0;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $ch2AmountOfLiters = 0;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $ch3AmountOfLiters = 0;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $ch4AmountOfLiters = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getCh1AmountOfLiters(): ?float
    {
        return $this->ch1AmountOfLiters;
    }

    public function setCh1AmountOfLiters(float $ch1AmountOfLiters): self
    {
        $this->ch1AmountOfLiters = $ch1AmountOfLiters;
        return $this;
    }

    public function getCh2AmountOfLiters(): ?float
    {
        return $this->ch2AmountOfLiters;
    }

    public function setCh2AmountOfLiters(float $ch2AmountOfLiters): self
    {
        $this->ch2AmountOfLiters = $ch2AmountOfLiters;
        return $this;
    }

    public function getCh3AmountOfLiters(): ?float
    {
        return $this->ch3AmountOfLiters;
    }

    public function setCh3AmountOfLiters(float $ch3AmountOfLiters): self
    {
        $this->ch3AmountOfLiters = $ch3AmountOfLiters;
        return $this;
    }

    public function getCh4AmountOfLiters(): ?float
    {
        return $this->ch4AmountOfLiters;
    }

    public function setCh4AmountOfLiters(float $ch4AmountOfLiters): self
    {
        $this->ch4AmountOfLiters = $ch4AmountOfLiters;
        return $this;
    }

    public function getSeconds(): ?int
    {
        return $this->seconds;
    }

    public function setSeconds(int $seconds): self
    {
        $this->seconds = $seconds;
        return $this;
    }

    public function getInterval()
    {
        $minutes = floor($this->seconds/60);
        $seconds = $this->seconds-($minutes*60);

        return $minutes.'m '.$seconds.'s';
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'date' => DaysOfWeek::NAMES[$this->date->format('N')-1].' '.$this->date->format('d.m.Y H:i'),
            'interval' => $this->getInterval(),
            'ch1' => $this->ch1AmountOfLiters ? round($this->ch1AmountOfLiters,2).' litrów' : '-',
            'ch2' => $this->ch2AmountOfLiters ? round($this->ch2AmountOfLiters,2).' litrów' : '-',
            'ch3' => $this->ch3AmountOfLiters ? round($this->ch3AmountOfLiters,2).' litrów' : '-',
            'ch4' => $this->ch4AmountOfLiters ? round($this->ch4AmountOfLiters,2).' litrów' : '-',
        ];
    }
}