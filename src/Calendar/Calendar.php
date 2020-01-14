<?php

namespace App\Calendar;

use App\Calendar\Unit\Day;
use App\Calendar\Unit\Month;

class Calendar
{
    /** @var \DateTime */
    private $startDate;

    /** @var array */
    private $calendarData;

    /** @var Event[] */
    private $events;

    public function __construct(\DateTime $startDate=null)
    {
        $this->setStartDate($startDate);
    }

    public function generateCalendarData(int $numberOfMonths = 12): Calendar
    {
        $date = clone $this->startDate;
        $this->resetCalendar();

        for ($i=0; $i<$numberOfMonths; $i++) {
            $month = new Month($date->format('F'), $date->format('Y'));
            for ($day=1; $day<=$date->format('t'); $day++) {
                $month->addDay(
                    $this->prepareDayObject($date->format('Y-m') . '-' . $day)
                );
            }
            $this->calendarData[] = $month;
            $date->modify('+1 month');
        }

        return $this;
    }

    private function prepareDayObject($shortDate)
    {
        $date = new \DateTime($shortDate);
        $day = new Day();
        $day->setDate($date);

        if (!empty($this->events)) {
            $events = array_filter($this->events, function ($event) use ($date) {
                if (is_null($event->getEnd())) {
                    return $event->getStart()->format('Y-m-d') == $date->format('Y-m-d');
                }

                $dateString = $date->format('Y-m-d');
                return (
                    ($dateString >= $event->getStart()->format('Y-m-d'))
                    && ($dateString <= $event->getEnd()->format('Y-m-d'))
                );
            });
            $day->setEvents($events);
        }
        return $day;
    }

    public function setStartDate(\DateTime $startDate): void
    {
        $this->startDate = $startDate;
        $this->startDate->setTime(0,0);
    }

    private function resetCalendar(): void
    {
        $this->calendarData = [];
    }

    public function addEvent(Event $event): void
    {
        $this->events[] = $event;
    }

    public function setEvents(array $events)
    {
        $this->events = $events;
    }

    public function getData(): array
    {
        return $this->calendarData;
    }
}