<?php

namespace App\Repository;

use App\Calendar\Event;
use App\Calendar\Event\HolidayEvent;
use App\Serializer\Normalizer\EventNormalizer;
use App\Service\Loader\CSVLoader;
use App\Service\Loader\PackLoader;
use MessagePack\MessagePack;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;

class HolidaysRepository
{
    /** @var HolidayEvent[] */
    private $holidays;

    private $holidayIndex;

    /** @var CSVLoader */
    private $csvLoader;

    /** @var PackLoader */
    private $msgPackLoader;

    /** @var Serializer  */
    private $serializer;

    public function __construct(CSVLoader $csvLoader, PackLoader $msgPackLoader)
    {
        $this->csvLoader = $csvLoader;
        $this->msgPackLoader = $msgPackLoader;
        $this->serializer = new Serializer(
            [
                new EventNormalizer(),
                new DateTimeNormalizer()
            ]
        );
    }

    public function getHolidays(): array
    {
        return $this->holidays;
    }

    public function loadHolidaysFromCsv(string $federal): void
    {
        $this->holidays = $this->csvLoader->readPublicHolidays($federal);
    }

    public function getPackedPublicHolidays(string $federal): array
    {
        $filteredHolidays = $this->msgPackLoader->readPublicHolidays($federal);
        $holidays = [];
        foreach ($filteredHolidays as $data) {
            $holidays[] = $this->serializer->denormalize(
                $data['holiday'],
                Event::class,
                null,
                ['eventType' => Event\Types::EVENT_TYPE_PUBLIC_HOLIDAY]
            );
        }
        return $holidays;
    }

    public function getPackedSchoolHolidays(string $federal): array
    {
        $filteredHolidays = $this->msgPackLoader->readSchoolHolidays($federal);
        $holidays = [];
        foreach ($filteredHolidays as $data) {
            $holidays[] = $this->serializer->denormalize(
                $data,
                Event::class,
                null,
                ['eventType' => Event\Types::EVENT_TYPE_SCHOOL_HOLIDAY]
            );
        }
        return $holidays;
    }

    public function savePublicHolidaysToPacked(array $data):void
    {
        $dataFile = $this->getDataDir() . '/publicHolidays.mpack';

        $f = fopen($dataFile, 'w+b');
        if (!empty($data)) {
            $packedData = MessagePack::pack($data);
            fwrite($f, $packedData, strlen($packedData));
        }
        fclose($f);
    }

    public function saveSchoolHolidaysToPacked(array $data):void
    {
        $dataFile = $this->getDataDir() . '/schoolHolidays.mpack';

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