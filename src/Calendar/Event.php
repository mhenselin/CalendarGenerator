<?php

namespace App\Calendar;

use App\Calendar\Event\Types;

class Event
{
    /** @var \DateTime */
    private $start;

    /** @var \DateTime */
    private $end;

    private $text;

    /** @var string */
    private $type;

    /** @var array */
    private $additionalInformation;

    public function __construct($type=Types::EVENT_TYPE_CUSTOM)
    {
        $this->type = $type;
    }

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

    public function isInRange(\DateTime $start, \DateTime $end): bool
    {
        if (empty($this->end)) {
            return ($this->start->format('Y-m-d') >= $start->format('Y-m-d'));
        }

        return ($this->start->format('Y-m-d') >= $start->format('Y-m-d')) &&
            ($this->end->format('Y-m-d') <= $end->format('Y-m-d'));
    }

    public function getType()
    {
        return $this->type;
    }
}