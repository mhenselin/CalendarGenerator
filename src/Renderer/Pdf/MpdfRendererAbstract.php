<?php

namespace App\Renderer\Pdf;

use App\Calendar\Calendar;
use Mpdf\Mpdf;

abstract class MpdfRendererAbstract implements RendererInterface
{
    /**
     * @var int
     */
    protected $colWidth = 0;

    /**
     * @var int
     */
    protected $rowHeight = 0;

    /**
     * @var int
     */
    protected $marginTop = 8;
    /**
     * @var int
     */
    protected $marginBottom = 7;
    /**
     * @var int
     */
    protected $marginLeft = 5;
    /**
     * @var int
     */
    protected $marginRight = 5;

    /**
     * @var float
     */
    protected $calenderStartY = 20;

    /**
     * @var int
     */
    protected $headerHeight = 6;

    /** @var Mpdf */
    protected $mpdf;

    /** @var Calendar */
    protected $calendar;

    protected function initMpdf(array $options=[], string $displaymode='fullpage' ): void
    {
        $this->mpdf = new Mpdf($options);

        $this->mpdf->setLogger(new class extends \Psr\Log\AbstractLogger {
            public function log($level, $message, $context=[])
            {
                echo $level . ': ' . $message . PHP_EOL;
            }
        });

        $this->mpdf->SetDisplayMode($displaymode);
        $this->mpdf->SetFontSize(6);
    }

    protected function calculateTableDimentions(int $months=12, int $maxRows=31): void
    {
        if (empty($this->mpdf)) {
            return;
        }

        $canvasSizeX = $this->mpdf->w;
        $canvasSizeY = $this->mpdf->h;
        $this->colWidth = round(
            ($canvasSizeX-($this->marginLeft+$this->marginRight))/$months,
            3
        );
        $this->rowHeight = round(
            ($canvasSizeY-($this->calenderStartY+$this->headerHeight))/$maxRows,
            3
        );
    }

    public function setCalendar(Calendar $calendar): void
    {
        $this->calendar = $calendar;
    }
}