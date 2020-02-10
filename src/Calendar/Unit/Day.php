<?php

namespace App\Calendar\Unit;

class Day
{
    /** @var \DateTime */
    private $date;

    public function setDate(\DateTime $date):void
    {
        $this->date = $date;
    }

    public function getDay(): int
    {
        return $this->date->format('j');
    }

    public function getWeekdayName(): string
    {
        return strftime('%a', $this->date->getTimestamp());
    }

    public function getFormatedDate($showWeekdayName=true)
    {
        return strftime(
            $showWeekdayName ? '%d %a' : '%d',
            $this->date->getTimestamp()
        );
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getDayOfWeek()
    {
        return $this->date->format('N');
    }
}