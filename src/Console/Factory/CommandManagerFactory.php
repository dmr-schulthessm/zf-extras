<?php

namespace ZfExtra\Console\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\Console\CommandManager;

class CommandManagerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $instance = new CommandManager();
        $instance->setServiceLocator($serviceLocator);
        return $instance;
    }

}
