<?php

namespace ZfExtra\Console\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\Console\ConsoleController;

class ConsoleControllerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ConsoleController($serviceLocator->getServiceLocator()->get('cli'));
    }

}
