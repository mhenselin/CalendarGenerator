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

    public function getDayOfMonth(): int
    {
        return $this->date->format('j');
    }

    public function getWeekdayName(): string
    {
        return $this->date->format('D');
    }

    public function getFormatedDate($showWeekdayName=true)
    {
        return $this->date->format($showWeekdayName ? 'j D' : 'j');
    }

    public function getBankHolidayIn()
    {
        return $this->bankHoliday;
    }

    public function hasBankHolidays()
    {
        return (empty($this->bankHoliday));
    }

    public function getSchoolHolidayIn()
    {
        return $this->schoolHoliday;
    }

    public function hasSchoolHolidays()
    {
        return (empty($this->schoolHoliday));
    }

    public function getEvents()
    {
        return $this->events;
    }

    public function hasEvents()
    {
        return (empty($this->events));
    }
}