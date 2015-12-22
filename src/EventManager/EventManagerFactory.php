<?php

namespace ZfExtra\EventManager;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EventManagerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get('Application')->getEventManager();
    }

}
