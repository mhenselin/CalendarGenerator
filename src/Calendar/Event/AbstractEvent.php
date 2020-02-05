<?php

namespace App\Calendar\Event;

abstract class AbstractEvent
{
    const EVENT_TYPE_HOLIDAY = 'bankHoliday';

    /** @var \DateTime */
    private $start;

    /** @var \DateTime */
    private $end;

    private $text;

    /** @var array */
    private $additionalInformation;

    public function getStart(): \DateTime
    {
        return $this->start;
    }

    public function setStart(\DateTime $start): void
    {
        $this->start = $start;
    }

    public function getEnd(): ?\DateTime
    {
        return $this->end;
    }

    public function setEnd(\DateTime $end): void
    {
        $this->end = $end;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText($text): void
    {
        $this->text = $text;
    }

    public function getAdditionalInformation(): array
    {
        return $this->additionalInformation;
    }

    public function setAdditionalInformation(array $info): void
    {
        $this->additionalInformation = $info;
    }

    public function dayHasEvent(\DteTime $day): bool
    {
        return false;
    }

    abstract public function getType():string;
}