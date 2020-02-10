<?php

namespace App\Service\Loader;

use App\Calendar\Event;
use App\Serializer\Normalizer\EventNormalizer;
use MessagePack\MessagePack;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;

class PackLoader extends LoaderAbstract
{
    private $serializer;

    public function __construct()
    {
        $this->serializer = new Serializer(
            [
                new EventNormalizer(),
                new DateTimeNormalizer()
            ]
        );
    }

    public function readHolidays(string $federal): array
    {
        $dataFile = $this->getDataPath() . '/publicHolidays.mpack';
        $filteredHolidays = array_filter(
            MessagePack::unpack(file_get_contents($dataFile)),
            function($holiday) use ($federal) {
                return in_array($federal, $holiday['holiday']['regions']);
            }
        );

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

}