<?php

namespace App\Repository;

use App\Calendar\Event;
use App\Service\CSVLoader;

class HolidaysRepository
{
    /** @var Event[] */
    private $holidays;

    private $holidayIndex;

    private $fileloader;

    public function __construct(CSVLoader $fileLoader)
    {
        $this->fileloader = $fileLoader;
    }

    public function getHolidays(): array
    {
        return $this->holidays;
    }

    public function loadHolidays(string $federal): void
    {
        $this->holidays = $this->fileloader->readHolidaysFromFile($federal);
    }


    public function getHolidaysByYear(string $year): array
    {
        return array_filter(
            $this->holidays,
            function ($data) use ($year) {
                return $data->getDate()->format('Y') === $year;
            }
        );
    }

    public function isHoliday($day, $month, $year): bool
    {
        return isset($this->holidayIndex[$year][$month][$day]);
    }
}