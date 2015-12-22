<?php

namespace ZfExtra\ModuleManager\Service;

use Closure;
use Zend\ModuleManager\ModuleManager;
use Zend\ServiceManager\DelegatorFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * This delegator add custom event listeners to module manager.
 * The listeners are triggered by ModuleManager class when it instantiates Module class.
 */
class ModuleManagerDelegator implements DelegatorFactoryInterface
{

    /**
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $name
     * @param string $requestedName
     * @param Closure $callback
     * @return ModuleManager
     */
    public function createDelegatorWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName, $callback)
    {
        /* @var $serviceLocator ServiceManager */
        $serviceLocator->setFactory('ModuleListenerManager', 'ZfExtra\ModuleManager\Service\ModuleListenerManagerFactory');
        
        /* @var $moduleManager ModuleManager */
        $moduleManager = $callback();
        
        $moduleListenerManager = $serviceLocator->get('ModuleListenerManager');
        $listeners = $moduleListenerManager->getCanonicalNames();

        foreach ($listeners as $listener) {
            $moduleManager->getEventManager()->attachAggregate($moduleListenerManager->get($listener));
        }
        
        return $moduleManager;
    }

}
