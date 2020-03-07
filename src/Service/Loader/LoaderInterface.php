<?php

namespace App\Service\Loader;

interface LoaderInterface
{
    public function readPublicHolidays(string $federal): array;
}