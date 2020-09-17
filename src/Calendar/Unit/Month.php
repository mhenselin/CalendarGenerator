<?php

namespace App\Calendar\Unit;

class Month
{
    /** @var Day[] */
    private $days = [];

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
        foreach ($days as $key => $day) {
            if (!is_object($day) || get_class($day) !== Day::class) {
                throw new \Exception('Days to be set contain non Day object at key ' . $key);
            }
            $this->days[$day->getDay()] = $day;
        }
    }

    public function addDay(Day $day): void
    {
        $this->days[$day->getDay()] = $day;
    }

    public function getDays(): array
    {
        return $this->days;
    }

    public function getName():string
    {
        return $this->name;
    }

    public function getYear(bool $short=false):string
    {
        return !$short ? $this->year : substr($this->year, -2);
    }

    public function getDayOfMonth(int $day): ?Day
    {
        return isset($this->days[$day]) ? $this->days[$day] : null;
    }

    public function getFirstDay(): ?Day
    {
        return isset($this->days[1]) ? $this->days[1] : null;
    }

    public function getLastDay(): ?Day
    {
        return count($this->days) != 0 ? $this->days[count($this->days)] : null;
    }
}
