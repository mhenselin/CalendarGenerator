<?php

namespace App\Renderer\Pdf;

interface RendererInterface
{
    public function renderData();

    public function setCalendarData($calendarData): void;
}
