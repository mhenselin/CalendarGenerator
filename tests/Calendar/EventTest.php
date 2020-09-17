<?php

namespace App\Tests\Calendar;

use App\Calendar\Event;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{


    /**
     */
    public function testEventCreation()
    {
        $start = new \DateTime('now');
        $end = new \DateTime('now');
        $text = 'Test';
        $additionalInfo = [];
        $eventType = Event\Types::EVENT_TYPE_CUSTOM;

        $eventTest = new Event($eventType);
        $eventTest->setText($text);
        $eventTest->setStart($start);
        $eventTest->setEnd($end);
        $eventTest->setAdditionalInformation($additionalInfo);

        $this->assertEquals($eventType, $eventTest->getType());
        $this->assertEquals($text, $eventTest->getText());
        $this->assertEquals($additionalInfo, $eventTest->getAdditionalInformation());
        $this->assertEquals($start, $eventTest->getStart());
        $this->assertEquals($end, $eventTest->getEnd());
    }

    public function getEventData()
    {
        return [
            'Within' => [
                new \DateTime('2017-01-01 10:00:00'),
                new \DateTime('2017-01-02 10:00:00'),
                new \DateTime('2016-12-31 10:00:00'),
                new \DateTime('2017-01-03 10:00:00'),
                true
            ],
            'Outside' => [
                new \DateTime('2017-01-01 10:00:00'),
                new \DateTime('2017-01-02 10:00:00'),
                new \DateTime('2017-01-04 10:00:00'),
                new \DateTime('2017-01-05 10:00:00'),
                false
            ],
            'EndIn' => [
                new \DateTime('2017-01-01 10:00:00'),
                new \DateTime('2017-01-02 10:00:00'),
                new \DateTime('2017-01-02 07:00:00'),
                new \DateTime('2017-01-05 10:00:00'),
                false
            ],
            'StartIn' => [
                new \DateTime('2017-01-01 10:00:00'),
                new \DateTime('2017-01-02 10:00:00'),
                new \DateTime('2017-01-01 07:00:00'),
                new \DateTime('2017-01-02 07:00:00'),
                false
            ],
            'NoEnd' => [
                new \DateTime('2017-01-01 10:00:00'),
                null,
                new \DateTime('2017-01-01 07:00:00'),
                new \DateTime('2017-01-02 07:00:00'),
                true
            ],
        ];
    }

    /**
     * @dataProvider getEventData
     */
    public function testInRange($start, $end, $testStart, $testEnd, bool $isInRange)
    {
        $eventTest = new Event(Event\Types::EVENT_TYPE_CUSTOM);
        $eventTest->setStart($start);
        if (!is_null($end)) {
            $eventTest->setEnd($end);
            $this->assertTrue($eventTest->isInRange($start, $end));
        }

        $this->assertEquals($isInRange, $eventTest->isInRange($testStart, $testEnd));
    }
}
