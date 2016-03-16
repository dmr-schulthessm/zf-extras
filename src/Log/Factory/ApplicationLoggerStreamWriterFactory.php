<?php

namespace ZfExtra\Log\Factory;

use Zend\Log\Writer\Stream;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@muehlemann-popp.ch>
 */
class ApplicationLoggerStreamWriterFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Stream('data/logs/app.log');
    }

}
