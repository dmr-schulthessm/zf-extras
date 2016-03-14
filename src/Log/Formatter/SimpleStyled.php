<?php


namespace ZfExtra\Log\Formatter;

use Symfony\Component\Console\Formatter\OutputFormatter;
use Zend\Log\Formatter\Simple;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@muehlemann-popp.ch>
 */
class SimpleStyled extends Simple
{
    public function format($event)
    {
        $message = parent::format($event);
        $colorizer = new OutputFormatter(true);
        return $colorizer->format($message);
    }
}
