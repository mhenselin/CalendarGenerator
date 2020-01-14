<?php

namespace App\Serializer\Normalizer;

use App\Calendar\Event\AbstractEvent;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

abstract class AbstractCalendarNormalizer implements NormalizerInterface, DenormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    abstract public function getClass();

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return is_subclass_of($type, AbstractEvent::class);
    }

}