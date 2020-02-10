<?php

namespace App\Service\Loader;

use App\Calendar\Event;
use App\Serializer\Normalizer\EventNormalizer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;

class CSVLoader extends LoaderAbstract
{
    private $serializer;

    public function __construct()
    {
        $this->serializer = new Serializer(
            [
                new EventNormalizer(),
                new DateTimeNormalizer()
            ],
            [new CsvEncoder()]
        );
    }

    public function readHolidays(string $federal): array
    {
        $filename = $this->getDataPath() . '/holidays/Holidays_' . $federal . '.csv';
        if (!file_exists($filename)) {
            throw new \Exception('can not find data file ' . $filename);
        }

        $holidays = [];
        $csvData = file_get_contents($filename);
        foreach ($this->serializer->decode($csvData, 'csv') as $data) {
            $holidays[] = $this->serializer->denormalize($data, Event::class);
        }
        return $holidays;
    }
}