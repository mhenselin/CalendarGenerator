<?php

namespace App\Calendar;

use App\Calendar\Unit\Day;
use App\Calendar\Unit\Month;
use Carbon\Carbon;

class Calendar
{
    /** @var \DateTime */
    private $startDate;

    /** @var array */
    private $calendarData = [];

    /** @var Event[] */
    private $events = [];

    public function __construct(\DateTime $startDate=null)
    {
//        if ($startDate === null) {
//            $startDate = new \DateTime();
//        }
//        $this->setStartDate($startDate);
		$this->setStartDate($startDate ?? Carbon::now());
    }

    public function generateCalendarData(int $numberOfMonths = 12): Calendar
    {
		$date = clone $this->startDate;
        $this->resetCalendar();

        for ($i=0; $i<$numberOfMonths; $i++) {
			$month = new Month(
				$date->locale('de')->monthName,
                $date->year
            );
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

	/**
	 * @param $shortDate
	 * @return Day
	 * @throws \Exception
	 */
    private function prepareDayObject($shortDate): Day
	{
        $date = new \DateTime($shortDate);
        $day = new Day();
        $day->setDate($date);

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

    public function addEvents(array $events): void
    {
        if (!empty($events)) {
            $this->events = array_merge($this->events, $events);
        }
    }

    public function setEvents(array $events)
    {
        $this->events = $events;
    }

    public function getData(): array
    {
        return $this->calendarData;
    }

    public function getActiveCalendarEvents(): array
    {
        $start = $this->startDate;
        $end = clone $start;
        $end->modify('+' . count($this->calendarData) . ' month');
        return array_filter($this->events, function ($event) use ($start, $end) {
            /** @var Event $event */
            return $event->isInRange($start, $end);
        });
    }
}