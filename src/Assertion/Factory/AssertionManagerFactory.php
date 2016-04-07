<?php

namespace ZfExtra\Assertion\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\Assertion\AssertionManager;

class AssertionManagerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $instance = new AssertionManager;
        $instance->setServiceLocator($serviceLocator);
        return $serviceLocator;
    }

}
