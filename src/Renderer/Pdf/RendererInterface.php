<?php

namespace App\Renderer\Pdf;

interface RendererInterface
{
    public function renderData(string $file = ''): ?string;
    public function setCalendarData($calendarData): void;
}
