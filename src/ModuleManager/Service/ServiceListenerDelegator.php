<?php

namespace ZfExtra\ModuleManager\Service;

use Zend\ModuleManager\Listener\ServiceListener;
use Zend\ServiceManager\DelegatorFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;
use ZfExtra\ModuleManager\Feature\AssertionProviderInterface;
use ZfExtra\ModuleManager\Feature\CommandProviderInterface;

/**
 * This delegator adds custom service managers.
 */
class ServiceListenerDelegator implements DelegatorFactoryInterface
{

    public function createDelegatorWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName, $callback)
    {
        /* @var $serviceLocator ServiceManager */
        /* @var $serviceListener ServiceListener */
        $serviceListener = $callback();
        
        $serviceLocator->setFactory('CommandManager', 'ZfExtra\Console\Service\CommandManagerFactory');
        $serviceListener->addServiceManager('CommandManager', 'command_manager', CommandProviderInterface::class, 'getCommandsConfig');
        
        /* @var $serviceLocator ServiceManager */
        $serviceLocator->setFactory('AssertionManager', 'ZfExtra\Assertion\Service\AssertionManagerFactory');
        $serviceListener->addServiceManager('AssertionManager', 'assertions', AssertionProviderInterface::class, 'getAssertionsConfig');
        
        return $serviceListener;
    }

}
