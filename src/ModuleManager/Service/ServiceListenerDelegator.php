<?php

namespace ZfExtra\ModuleManager\Service;

use Zend\ModuleManager\Listener\ServiceListener;
use Zend\ServiceManager\DelegatorFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;
use ZfExtra\Assertion\Factory\AssertionManagerFactory;
use ZfExtra\Console\Factory\CommandManagerFactory;
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

        $serviceLocator->setFactory('CommandManager', CommandManagerFactory::class);
        $serviceListener->addServiceManager('CommandManager', 'command_manager', CommandProviderInterface::class, 'getCommandsConfig');

        /* @var $serviceLocator ServiceManager */
        $serviceLocator->setFactory('AssertionManager', AssertionManagerFactory::class);
        $serviceListener->addServiceManager('AssertionManager', 'assertions', AssertionProviderInterface::class, 'getAssertionsConfig');

        return $serviceListener;
    }

}
