<?php

namespace App\Renderer;

use App\Calendar\Event;
use App\Calendar\Unit\Day;
use App\Calendar\Unit\Month;
use App\Renderer\LandscapeYear\EventRendererFactory;
use App\Renderer\Pdf\CalendarDimension;
use App\Renderer\Pdf\MpdfRendererAbstract;
use Mpdf\Output\Destination;

class LandscapeYearMpdf extends MpdfRendererAbstract
{
    CONST FONT_SIZE_HEADER = 8;
    CONST FONT_SIZE_CELL = 6;
    CONST COLOR_TEXT_HEADER = '#c63131';
    const COLOR_BORDER_TABLE = '#c63131';
    CONST COLOR_BORDER_HEADER = '#DEDEDE';
    const COLOR_FILL_SA = '#F8E6E6';
    const COLOR_FILL_SO = '#F3D5D5';

    /** @var Month[] */
    private $calendarData;

    /** @var Event[] */
    private $calendarEvents;

    private $monthCount = 12;

    private $crossYears = false;

    private $fillColorWeekday = [
        6 => self::COLOR_FILL_SA,
        7 => self::COLOR_FILL_SO
    ];

    public function renderCalendar(string $file = ''): ?string
    {
        $this->initMpdf([
            'format' => 'A4-L',
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 10,
            'margin_bottom' => 0,
        ]);
        $this->mpdf->AddPage();
        $this->mpdf->SetFont('Helvetica');

        $this->calculateTableDimentions(count($this->calendarData));
        $this->validateCalendarData();
        $this->renderHeader();
        $this->renderData();
        $this->renderEvents();

        $redBorder = $this->hex2rgb(self::COLOR_BORDER_TABLE);
        $this->mpdf->SetDrawColor($redBorder[0], $redBorder[1], $redBorder[2]);
        $this->mpdf->Rect(
            $this->mpdf->lMargin-2,
            $this->mpdf->tMargin,
            $this->monthCount * $this->colWidth + 2,
            31 * $this->rowHeight + $this->headerHeight + 2
        );

        if (!empty($file)) {
            $this->mpdf->Output($file, Destination::FILE);
            return '';
        } else {
            return $this->mpdf->Output();
        }
    }

    private function renderHeader()
    {
        $this->mpdf->SetFontSize(self::FONT_SIZE_HEADER);
        $this->mpdf->SetFont('', 'B');
        $borderColor = $this->hex2rgb(self::COLOR_BORDER_HEADER);
        $textColor = $this->hex2rgb(self::COLOR_TEXT_HEADER);
        $this->mpdf->SetDrawColor($borderColor[0], $borderColor[1], $borderColor[2]);
        $this->mpdf->SetTextColor($textColor[0], $textColor[1], $textColor[2]);

        /** @var Month $month */
        foreach ($this->calendarData as $key => $month) {
            $text = !$this->crossYears ? $month->getName() : $month->getName() . ' `' .$month->getYear(true);
            $this->mpdf->WriteCell($this->colWidth, $this->headerHeight , $text, 'B', 0, 'C');
        }
    }

    public function renderData(): void
    {
        $this->mpdf->SetFontSize(self::FONT_SIZE_CELL);
        $this->mpdf->SetTextColor(0, 0, 0);
        $startHeight = $this->mpdf->tMargin + $this->headerHeight;

        /** @var  Month$month */
        foreach ($this->calendarData as $key => $month) {
            /** @var Day $day */
            foreach ($month->getDays() as $dom => $day) {
                $fill = 0;
                $this->mpdf->SetXY(
                    $this->mpdf->lMargin + ($key * $this->colWidth),
                    $startHeight + (($dom-1) * $this->rowHeight)
                );

                $text = $day->getDay() . ' ' . $day->getWeekdayName();
                $colorData = $this->getDayColorData($day);
                if ($colorData['fill']) {
                    $this->mpdf->SetFillColor($colorData['color'][0], $colorData['color'][1], $colorData['color'][2]);
                }

                $this->mpdf->Cell(
                    $this->colWidth-1,
                    $this->rowHeight ,
                    $text,
                    'B',
                    0,
                    '',
                    $colorData['fill']);
            }
        }
    }

    private function getDayColorData(Day $day): array
    {
        $colorData = [
            'fill' => false,
            'color' => [0,0,0]
        ];

        $dow = $day->getDayOfWeek();
        if ($day->getDayOfWeek() > 5) {
            $colorData['fill'] = 1;
            if (isset($this->fillColorWeekday[$dow])) {
                $colorData['color'] = $this->hex2rgb($this->fillColorWeekday[$dow]);
            }
        }

        return $colorData;
    }
    private function validateCalendarData():void
    {
        $this->monthCount = count($this->calendarData);
        $this->crossYears = $this->calendarData[0]->getYear() != $this->calendarData[$this->monthCount-1]->getYear();
    }

    /**
     * @param mixed $calendarData
     */
    public function setCalendarData($calendarData): void
    {
        $this->calendarData = $calendarData;
    }

    public function setCalendarEvents($events): void
    {
     $this->calendarEvents = $events;
    }

    private function renderEvents():void
    {
        if (empty($this->calendarEvents)) {
            return;
        }

        $dimension = new CalendarDimension();
        $dimension->setLeft($this->mpdf->lMargin)
            ->setTop($this->mpdf->tMargin)
            ->setColumnWidth($this->colWidth)
            ->setRowHeight($this->rowHeight)
            ->setHeaderHeight($this->headerHeight);

        /** @var Event $event */
        foreach ($this->calendarEvents as $event) {
            $renderer = EventRendererFactory::getRendererFor($event->getType(), $this->mpdf);
            $renderer->render($event, $dimension);
        }
    }
}