<?php

namespace App\Calendar\Unit;

class Day
{
    /** @var \DateTime */
    private $date;

    private $bankHoliday;

    private $shoolHoliday;

    /**
     * @param mixed $bankHoliday
     */
    public function setBankHoliday($bankHoliday): void
    {
        $this->bankHoliday = $bankHoliday;
    }

    /**
     * @param mixed $shoolHoliday
     */
    public function setShoolHoliday($shoolHoliday): void
    {
        $this->shoolHoliday = $shoolHoliday;
    }

    /**
     * @param mixed $events
     */
    public function setEvents($events): void
    {
        $this->events = $events;
    }

    private $events;

    public function setDate(\DateTime $date):void
    {
        $this->date = $date;
    }

    public function getDay(): int
    {
        return $this->date->format('j');
        #return strftime('%e', $this->date->getTimestamp());
    }

    public function getWeekdayName(): string
    {
        #return $this->date->format('D');
        return strftime('%a', $this->date->getTimestamp());
    }

    public function getFormatedDate($showWeekdayName=true)
    {
        #return $this->date->format($showWeekdayName ? 'j D' : 'j');
        return strftime(
            $showWeekdayName ? '%d %a' : '%d',
            $this->date->getTimestamp()
        );
    }

    public function getEvents()
    {
        return $this->events;
    }

    public function hasEvents()
    {
        return (empty($this->events));
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