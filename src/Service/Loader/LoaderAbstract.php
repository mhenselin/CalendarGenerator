<?php


namespace App\Service\Loader;


abstract class LoaderAbstract implements LoaderInterface
{
    protected function getDataPath()
    {
        return realpath(__DIR__ . '/../../../data');
    }
}