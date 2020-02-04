<?php

namespace App\Renderer\LandscapeYear;

use App\Calendar\Event\AbstractEvent;
use App\Renderer\Pdf\AdditionsRendererInterface;
use Mpdf\Mpdf;

class EventRenderer implements AdditionsRendererInterface
{
    /** @var Mpdf */
    private $mpdf;

    public function setPdfClass($pdfClass): void
    {
        $this->mpdf = $pdfClass;
    }

    public function render(AbstractEvent $event): void
    {
        // TODO: Implement render() method.
    }
}