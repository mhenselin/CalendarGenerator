<?php

namespace App\Service;

use App\Calendar\Event\HolidayEvent;
use App\Repository\HolidaysRepository;
use App\Serializer\Normalizer\HolidayCalendarNormalizer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class CSVLoader
{
    private $serializer;

    public function __construct()
    {
        $this->serializer = new Serializer(
            [
                new HolidayCalendarNormalizer(),
                new DateTimeNormalizer()
            ],
            [new CsvEncoder()]
        );
    }

    public function readHolidaysFromFile(string $federal)
    {
        $filename = $this->getDataPath() . '/holidays/Holidays_' . $federal . '.csv';
        if (!file_exists($filename)) {
            throw new \Exception('can not find data file ' . $filename);
        }

        $holidays = [];
        $csvData = file_get_contents($filename);
        foreach ($this->serializer->decode($csvData, 'csv') as $data) {
            $holidays[] = $this->serializer->denormalize($data, HolidayEvent::class);
        }
        return $holidays;
    }

    private function getDataPath()
    {
        return realpath(__DIR__ . '/../../data');
    }
}