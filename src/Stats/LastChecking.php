<?php


namespace App\Stats;


class LastChecking
{
    const LAST_CHECKED_FILENAME = __DIR__.'/../../last-checked-history.txt';

    /**
     * @return \DateTime
     */
    public static function get()
    {
        if(!is_readable(self::LAST_CHECKED_FILENAME)) {
            return new \DateTime('1999-01-01');
        }

        return new \DateTime(file_get_contents(self::LAST_CHECKED_FILENAME));
    }

    /**
     * @param \DateTime $dateTime
     */
    public static function set(\DateTime $dateTime)
    {
        file_put_contents(self::LAST_CHECKED_FILENAME, $dateTime->format('Y-m-d H:i:s'));
    }
}