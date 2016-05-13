<?php

namespace ZfExtra\Mvc\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\Mvc\Listener\DoctrineObjectInjectionListener;

class DoctrineObjectInjectionListenerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $instance = new DoctrineObjectInjectionListener;
        $instance->setServiceLocator($serviceLocator);
        return $instance;
    }

}
