<?php

namespace App\Calendar\Event;

use App\Calendar\Event;

class HolidayEvent extends AbstractEvent
{
    public function getType(): string
    {
        AbstractEvent::EVENT_TYPE_HOLIDAY;
    }
}