<?php

namespace App\Repository;

use App\Calendar\Event\HolidayEvent;
use App\Service\Loader\CSVLoader;
use App\Service\Loader\PackLoader;
use MessagePack\MessagePack;

class HolidaysRepository
{
    /** @var HolidayEvent[] */
    private $holidays;

    private $holidayIndex;

    /** @var CSVLoader */
    private $csvLoader;

    /** @var PackLoader */
    private $msgPackLoader;

    public function __construct(CSVLoader $csvLoader, PackLoader $msgPackLoader)
    {
        $this->csvLoader = $csvLoader;
        $this->msgPackLoader = $msgPackLoader;
    }

    public function getHolidays(): array
    {
        return $this->holidays;
    }

    public function loadHolidaysFromCsv(string $federal): void
    {
        $this->holidays = $this->csvLoader->readHolidays($federal);
    }

    public function getPackedHolidays(string $federal): array
    {
        return $this->msgPackLoader->readHolidays($federal);
    }

    public function saveHolidaysToPacked(array $data):void
    {
        $dataFile = $this->getDataDir() . '/publicHolidays.mpack';

        $f = fopen($dataFile, 'w+b');
        if (!empty($data)) {
            $packedData = MessagePack::pack($data);
            fwrite($f, $packedData, strlen($packedData));
        }
        fclose($f);
    }

    private function getDataDir(): string
    {
        return realpath(__DIR__ . '/../../data');
    }
}