<?php

namespace ZfExtra\Log\Factory;

use Zend\Log\Formatter\Simple;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@muehlemann-popp.ch>
 */
class ApplicationLoggerFormatterFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Simple;
    }

}
