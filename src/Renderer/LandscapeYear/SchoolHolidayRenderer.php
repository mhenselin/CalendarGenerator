<?php

namespace App\Renderer\LandscapeYear;

use App\Calendar\Event;
use App\Renderer\Pdf\CalendarDimension;
use App\Service\RenderUtils;

class SchoolHolidayRenderer extends AbstractRenderer
{
    const COLORS_SCHOOL_HOLIDAY = [
        '#11CC11',
        '#82f082',
        '#41ff24'
    ];

    const HOLIDAY_WIDTH = 6;

    public function render(Event $event, CalendarDimension $dimensions, \DateTime $calendarStart, \DateTime $calendarEnd): void
    {
        if ($event->isInRange($calendarStart, $calendarEnd)) {
            echo $event->getText() . ' ' . $event->getStart()->format('Y') .
                 ' - ' .  $event->getStart()->format('d.m.') . '-' . $event->getEnd()->format('d.m.') . PHP_EOL;


            $start = $event->getStart();
            $end = $event->getEnd();
            $monthStart = $start->format('m');
            $monthEnd = $end->format('m');

            for ($i=$monthStart; $i<=$monthEnd; $i++) {
                $x = $dimensions->getLeft() +
                    (($i - 1) * $dimensions->getColumnWidth()) +
                    $dimensions->getColumnWidth() - self::HOLIDAY_WIDTH - 1;
                $y = ($start->format('d') - 1) * $dimensions->getRowHeight() +
                    $dimensions->getTop() +
                    $dimensions->getHeaderHeight();

                if ($i == $monthEnd) {
                    $days = (int) $end->format('d') - (int) $start->format('d') + 1;
                } else {
                    $newStart = clone $start;
                    $newStart->modify('last day of this month');
                    $days = (int) $newStart->format('d') - (int) $start->format('d') + 1;
                    $newStart->modify('+1 day');
                    $start = $newStart;
                }

                $height = $days * $dimensions->getRowHeight();
                $drawColor = RenderUtils::hex2rgb(self::COLORS_SCHOOL_HOLIDAY[2]);

                $this->mpdf->SetFillColor($drawColor[0], $drawColor[1], $drawColor[2]);
                $this->mpdf->Rect(
                    $x,
                    $y,
                    6,
                    $height,
                    "F"
                );
            }
        }
    }

}