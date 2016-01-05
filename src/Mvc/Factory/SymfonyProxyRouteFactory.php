<?php

namespace ZfExtra\Mvc\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\Mvc\Router\Console\SymfonyProxyRoute;

class SymfonyProxyRouteFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceLocator = $serviceLocator->getServiceLocator();
        return new SymfonyProxyRoute($serviceLocator->get('cli'), $serviceLocator->get('config.helper')->get('console.router.defaults'));
    }

}
