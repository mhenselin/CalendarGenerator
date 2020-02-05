<?php

namespace App\Serializer\Normalizer;

use App\Calendar\Event\AbstractEvent;
use App\Calendar\Event\HolidayEvent;
use Symfony\Component\Serializer\SerializerAwareTrait;

class HolidayCalendarNormalizer extends AbstractCalendarNormalizer
{
    use SerializerAwareTrait;

    public function normalize($object, string $format = null, array $context = [])
    {
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        if (!array_key_exists('date', $data) || !array_key_exists('name', $data)) {
            return null;
        }

        $entity = new HolidayEvent();
        $entity->setStart($this->serializer->denormalize($data['date'], \DateTime::class));
        $entity->setText($data['name']);

        return $entity;
    }

    public function supportsNormalization($data, string $format = null)
    {
        return is_subclass_of($format, AbstractEvent::class);
    }

    public function getClass()
    {
        return AbstractEvent::class;
    }
}