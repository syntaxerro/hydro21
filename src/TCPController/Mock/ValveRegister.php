<?php

namespace App\TCPController\Mock;


class ValveRegister
{
    public static $ch1 = false;

    public static $ch2 = false;

    public static $ch3 = false;

    public static $ch4 = false;

    public static $main = false;

    public static function setByGPIONumber($gpioNumber, bool $state)
    {
        switch($gpioNumber) {
            case 26:
                self::$ch1 = $state;
                break;

            case 19:
                self::$ch2 = $state;
                break;

            case 13:
                self::$ch3 = $state;
                break;

            case 6:
                self::$ch4 = $state;
                break;

            case 5:
                self::$main = $state;
                break;
        }
    }

    public static function getByGPIONumber($gpioNumber)
    {
        switch($gpioNumber) {
            case 26:
                return (int)self::$ch1;

            case 19:
                return (int)self::$ch1;

            case 13:
                return (int)self::$ch1;

            case 6:
                return (int)self::$ch1;

            case 5:
                return (int)self::$ch1;
        }

        return null;
    }
}