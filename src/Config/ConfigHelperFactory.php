<?php

namespace ZfExtra\Config;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\Config\ConfigHelper;

/**
 * Config helper factory.
 * 
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class ConfigHelperFactory implements FactoryInterface
{

    /**
     * Factory.
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @return ConfigHelper
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ConfigHelper($serviceLocator->get('Config'), $serviceLocator->get('ApplicationConfig'));
    }

}
