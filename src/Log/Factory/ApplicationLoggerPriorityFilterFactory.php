<?php

namespace ZfExtra\Log\Factory;

use Zend\Log\Filter\Priority;
use Zend\Log\Logger;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\Config\ConfigHelper;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@muehlemann-popp.ch>
 */
class ApplicationLoggerPriorityFilterFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $priority = $serviceLocator->get(ConfigHelper::class)->get('logger.priority', Logger::ERR);
        return new Priority($priority);
    }

}
