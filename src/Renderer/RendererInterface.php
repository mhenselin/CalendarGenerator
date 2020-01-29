<?php

namespace App\Renderer;

interface RendererInterface
{
    public function renderData();

    public function setCalendarData($calendarData): void;
}
