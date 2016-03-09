<?php

namespace ZfExtra\ModuleManager\Service;

use Zend\ServiceManager\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\ModuleManager\ModuleListenerManager;

class ModuleListenerManagerFactory implements FactoryInterface
{

    const CONFIG_KEY = 'module_event_listeners';

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('ApplicationConfig');
        if (!isset($config[self::CONFIG_KEY])) {
            $config[self::CONFIG_KEY] = array();
        }
        return new ModuleListenerManager(new Config($config[self::CONFIG_KEY]));
    }

}
