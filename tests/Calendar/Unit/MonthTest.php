<?php

namespace App\Tests\Calendar\Unit;

use App\Calendar\Unit\Day;
use App\Calendar\Unit\Month;
use PHPUnit\Framework\TestCase;

class MonthTest extends TestCase
{
    public function getMonthTestData()
    {
        $day1 = new Day();
        $day1->setDate(new \DateTime('2017-01-01'));
        $day2 = new Day();
        $day2->setDate(new \DateTime('2017-01-02'));

        return [
          'oneDaySet' => [
            'Januar', 2017, [$day1], 17, $day1, $day1, $day1, [1 => $day1]
          ]
        ];
    }

    /**
     * @dataProvider getMonthTestData
     */
    public function testMonth(string $name, int $year, array $days, int $shortYear, Day $dom, Day $fd, Day $ld, array $expectedDays)
    {
        $month = new Month($name, $year);
        $this->assertEquals($name, $month->getName());
        $this->assertEquals($year, $month->getYear());
        $this->assertEquals($shortYear, $month->getYear(true));
        $this->assertEquals(null, $month->getDayOfMonth(1));
        $this->assertEquals(null, $month->getFirstDay());
        $this->assertEquals(null, $month->getLastDay());
        $this->assertEmpty($month->getDays());

        $month->addDay($days[0]);
        $this->assertEquals($days[0], $month->getDayOfMonth(1));
        $this->assertEquals($days[0], $month->getFirstDay());
        $this->assertEquals($days[0], $month->getLastDay());
        $this->assertEquals([1 => $days[0]], $month->getDays());

        $month->setDays($days);
        $this->assertEquals($dom, $month->getDayOfMonth(1));
        $this->assertEquals($fd, $month->getFirstDay());
        $this->assertEquals($ld, $month->getLastDay());
        $this->assertEquals($expectedDays, $month->getDays());
    }

    public function testFailSetDays()
    {
        $this->expectExceptionMessage('Days to be set contain non Day object at key');
        $month = new Month('Januar', 1970);
        $month->setDays(['Tag1']);
    }
}
