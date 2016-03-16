<?php

namespace ZfExtra\Log\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\Log\EventListener\LogEventListener;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@muehlemann-popp.ch>
 */
class LogEventListenerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $logger = $serviceLocator->get('ZfExtra\Log\ApplicationLogger');
        return new LogEventListener($logger);
    }

}
