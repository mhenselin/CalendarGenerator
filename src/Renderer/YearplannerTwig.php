<?php

namespace App\Renderer;

use App\Calendar\Unit\Month;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Twig\Environment;

class YearplannerTwig
{
    private $calendarData;

    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @return mixed
     */
    public function getCalendarData()
    {
        return $this->calendarData;
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
            'margin_left' => 2,
            'margin_right' => 2,
            'margin_top' => 10,
            'margin_bottom' => 2,
        ]);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);
        $mpdf->Output('/Users/mathias.kuehn/priv-sources/CalendarGenerator/test.pdf', Destination::FILE);

        file_put_contents('/Users/mathias.kuehn/priv-sources/CalendarGenerator/calendertest.html', $html);
        var_dump($html);
        return $returnOutput ? $html : null;
    }

}