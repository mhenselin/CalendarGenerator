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

}