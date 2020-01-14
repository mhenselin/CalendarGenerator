<?php

namespace App\Calendar\Unit;

class Month
{
    /** @var Day[] */
    private $days;

    private $name;

    private $year;

    public function __construct($name, $year)
    {
        $this->name = $name;
        $this->year = $year;
    }

    /**
     * @param array $days
     */
    public function setDays(array $days): void
    {
        $this->days = $days;
    }

    public function addDay(Day $day): void
    {
        $this->days[$day->getDayOfMonth()] = $day;
    }

    public function getDays(): array
    {
        return $this->days;
    }

    public function getName():string
    {
        return $this->name;
    }

    public function getYear():string
    {
        return $this->year;
    }
}
