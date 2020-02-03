<?php

namespace App\Renderer\Pdf;

use App\Calendar\Event\AbstractEvent;

interface AdditionsRendererInterface
{
    public function setPdfClass($pdfClass): void;
    public function render(AbstractEvent $event): void;
}