<?php

namespace App\Renderer\LandscapeYear;

use App\Renderer\Pdf\AdditionsRendererInterface;
use Mpdf\Mpdf;

abstract class AbstractRenderer implements AdditionsRendererInterface
{
    /** @var Mpdf */
    protected $mpdf;

    public function setPdfClass($pdfClass): void
    {
        $this->mpdf = $pdfClass;
    }
}