<?php

namespace App\TCPController\SystemStatus;


use App\Locale\DaysOfWeek;

class DatetimeProvider
{
    public function get()
    {
        return DaysOfWeek::NAMES[date('N')-1].', '.date('d.m.Y H:i');
    }
}