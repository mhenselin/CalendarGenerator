<?php

namespace App\Service\Loader;

interface LoaderInterface
{
    public function readHolidays(string $federal): array;
}