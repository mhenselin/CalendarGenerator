<?php

namespace App\Tests\Calendar\Unit;

use App\Calendar\Unit\Day;
use PHPUnit\Framework\TestCase;

class DayTest extends TestCase
{
    public function testDay()
    {
        $dateTimeTest = new \DateTime('2020-09-17');
        $day = new Day();
        $day->setDate($dateTimeTest);

        $this->assertEquals($dateTimeTest, $day->getDate());
        $this->assertEquals(17, $day->getDay());
        $this->assertEquals(4, $day->getDayOfWeek());
        $this->assertEquals('Thu', $day->getWeekdayName());
        $this->assertEquals('17 Thu', $day->getFormatedDate());
        $this->assertEquals('17', $day->getFormatedDate(false));
    }

    public function testFailAddDateTime()
    {
        $this->expectExceptionMessage('must be an instance of DateTime');

        $day = new Day();
        $day->setDate('test');
    }
}
