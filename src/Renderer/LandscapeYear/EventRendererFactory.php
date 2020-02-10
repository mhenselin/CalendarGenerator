<?php

namespace App\Renderer\LandscapeYear;

use App\Renderer\Pdf\AdditionsRendererInterface;

class EventRendererFactory
{
    private static $renderer = [];

    public static function getRendererFor($type, $pdfClass): AdditionsRendererInterface
    {
        if (array_key_exists($type, self::$renderer)) {
            self::$renderer[$type]->setPdfClass($pdfClass);
            return self::$renderer[$type];
        }

        $className = 'App\Renderer\LandscapeYear\\' . ucfirst($type) . 'Renderer';
        if (class_exists($className)) {
            /** @var AdditionsRendererInterface $renderer */
            $renderer = new $className;
            $renderer->setPdfClass($pdfClass);
            self::$renderer[$type] = $renderer;
            return $renderer;
        }

        throw new EventRendererException('can not find event renderer for type ' . $type);
    }
}