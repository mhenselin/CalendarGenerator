<?php

namespace App\Calendar\Unit;

use Carbon\Carbon;

class Day
{
    /** @var Carbon $date */
    private $date;

	/**
	 * @param \DateTime|Carbon $date
	 */
    public function setDate(\DateTime $date):void
    {
    	Carbon::setLocale('de');
        $this->date = Carbon::parse($date);
    }

	/**
	 * @return int
	 */
    public function getDay(): int
    {
        return $this->date->day;
    }

	/**
	 * @return string
	 */
    public function getWeekdayName(): string
    {
        return $this->date->locale('de')->shortDayName;
    }

	/**
	 * @param bool $showWeekdayName
	 * @return string
	 */
    public function getFormattedDate($showWeekdayName=true): string
	{
    	if ($showWeekdayName) {
    		return $this->date->day . ' ' . $this->date->locale('de')->shortDayName;
		}
        return $this->date->day;
    }

	/**
	 * @return Carbon
	 */
    public function getDate(): Carbon
    {
        return $this->date;
    }

	/**
	 * @return int
	 */
    public function getDayOfWeek(): int
    {
        return $this->date->dayOfWeek;
    }
}