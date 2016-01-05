<?php

namespace ZfExtra\Mvc\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\Mvc\DoctrineObjectInjector;

class DoctrineObjectInjectorFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config.helper')->get('mvc.doctrine_object_injector');
        $objectManagers = [];
        foreach ($config['object_managers'] as $alias => $objectManager) {
            $objectManagers[$alias] = $serviceLocator->get($objectManager);
        }
        return new DoctrineObjectInjector($config, $objectManagers);
    }

}
