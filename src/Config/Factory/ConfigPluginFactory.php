<?php

namespace ZfExtra\Config\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\Config\ConfigHelper;
use ZfExtra\Config\Controller\Plugin\Config;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@muehlemann-popp.ch>
 */
class ConfigPluginFactory implements FactoryInterface
{

    /**
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @return Config
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Config($serviceLocator->getServiceLocator()->get(ConfigHelper::class));
    }

}
