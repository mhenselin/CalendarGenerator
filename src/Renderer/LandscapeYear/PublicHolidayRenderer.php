<?php

namespace App\Renderer\LandscapeYear;

use App\Calendar\Event;
use App\Renderer\Pdf\AdditionsRendererInterface;
use App\Renderer\Pdf\CalendarDimension;
use Mpdf\Mpdf;

class PublicHolidayRenderer extends AbstractRenderer
{
    const FONT_SIZE_HOLIDAY = 5;

    public function render(Event $event, CalendarDimension $dimensions, \DateTime $calendarStart, \DateTime $calendarEnd): void
    {
        $this->mpdf->SetFontSize(self::FONT_SIZE_HOLIDAY);
        $this->mpdf->SetFont('', 'B');
        $this->mpdf->SetTextColor(199, 50, 50);

        $month = $event->getStart()->format('m');
        $day = $event->getStart()->format('d');

        $x = $dimensions->getLeft() + (($month-1) * $dimensions->getColumnWidth());
        $y = $dimensions->getTop() +
            (($day-1) * $dimensions->getRowHeight()) +
            $dimensions->getHeaderHeight()  + 1.7;

        $this->mpdf->SetXY($x, $y);
        $this->mpdf->WriteText($x,$y, $event->getText());
    }

}