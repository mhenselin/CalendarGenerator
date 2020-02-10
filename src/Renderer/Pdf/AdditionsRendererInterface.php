<?php

namespace App\Renderer\Pdf;

use App\Calendar\Event;

interface AdditionsRendererInterface
{
    public function setPdfClass($pdfClass): void;
    public function render(Event $event, CalendarDimension $dimensions=null): void;
}