<?php


namespace App\Serializer\Normalizer;


use App\Entity\Event;
use Symfony\Component\Serializer\SerializerAwareTrait;

class EventNormlizer extends AbstractCalendarNormalizer
{
    use SerializerAwareTrait;

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {

    }

    public function normalize($object, string $format = null, array $context = [])
    {
        // TODO: Implement normalize() method.
    }

    public function supportsNormalization($data, string $format = null)
    {
        // TODO: Implement supportsNormalization() method.
    }


    public function getClass()
    {
        return Event::class;
    }
}