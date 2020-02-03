<?php

namespace App\Renderer;

use Dompdf\Dompdf;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Twig\Environment;

class LandscapeYearTwig implements RendererInterface
{
    private $calendarData;

    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }


    /**
     * @param mixed $calendarData
     */
    public function setCalendarData($calendarData): void
    {
        $this->calendarData = $calendarData;
    }

    public function renderData(bool $returnOutput=false): ?string
    {
        $html = $this->twig->render(
            'calendar/yearlyplaner/calendar.html.twig',
            [
                'calendar' => $this->calendarData
            ]);

        $mpdf = new Mpdf([
            'format' => 'A4-L',
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 10,
            'margin_bottom' => 0,
        ]);

        $mpdf->setLogger(new class extends \Psr\Log\AbstractLogger {
            public function log($level, $message, $context=[])
            {
                echo $level . ': ' . $message . PHP_EOL;
            }
        });

        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);
        $mpdf->Output('/Users/mathias.kuehn/priv-sources/CalendarGenerator/test.pdf', Destination::FILE);

        file_put_contents('/Users/mathias.kuehn/priv-sources/CalendarGenerator/calendertest.html', $html);
        return $returnOutput ? $html : null;
    }

}