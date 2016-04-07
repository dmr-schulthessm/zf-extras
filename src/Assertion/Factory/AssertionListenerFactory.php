<?php

namespace ZfExtra\Assertion\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\Assertion\Listener\AssertionListener;

class AssertionListenerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $instance = new AssertionListener;
        $instance->setServiceLocator($serviceLocator);
        return $instance;
    }

}
