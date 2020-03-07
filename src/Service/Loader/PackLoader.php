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
    public function readSchoolHolidays(string $federal): array
    {
        $dataFile = $this->getDataPath() . '/schoolHolidays.mpack';
        return array_map(
            function($vacation) use ($federal) {
                if (array_key_exists($federal, $vacation)) {
                    return [
                        'name' => $vacation['name'],
                        'start' => $vacation[$federal]['start'],
                        'end' => $vacation[$federal]['end'],
                    ];
                }
            },
            MessagePack::unpack(file_get_contents($dataFile)),
        );
    }

    public function readPublicHolidays(string $federal): array
    {
        $dataFile = $this->getDataPath() . '/publicHolidays.mpack';
        return array_filter(
            MessagePack::unpack(file_get_contents($dataFile)),
            function($holiday) use ($federal) {
                return in_array($federal, $holiday['holiday']['regions']);
            }
        );
    }

}