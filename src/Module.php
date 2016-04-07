<?php

namespace ZfExtra;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Listener\ServiceListenerInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\Assertion\AssertionManager;
use ZfExtra\Config\Config;
use ZfExtra\Console\CommandManager;
use ZfExtra\ModuleManager\Feature\AssertionProviderInterface;
use ZfExtra\ModuleManager\Feature\CommandProviderInterface;
use const DEBUG;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{

    public function init(ModuleManager $moduleManager)
    {
        /** @var ServiceLocatorInterface $sm */
//        $sm = $moduleManager->getEvent()->getParam('ServiceManager');
//
//        /** @var ServiceListenerInterface $serviceListener */
//        $serviceListener = $sm->get('ServiceListener');
//
//        $serviceListener->addServiceManager(CommandManager::class, 'config_key', CommandProviderInterface::class, 'getCommandsConfig');
//        $serviceListener->addServiceManager(AssertionManager::class, 'assertions', AssertionProviderInterface::class, 'getAssertionsConfig');
    }

    public function onBootstrap(MvcEvent $event)
    {
        $serviceManager = $event->getApplication()->getServiceManager();
        $translatorCache = $serviceManager->get('mvctranslator')->getCache();
        if ($translatorCache) {
            $translatorCache->setCaching(!DEBUG);
        }
    }

    /**
     * 
     * @return array
     */
    public function getConfig()
    {
        return Config::load(__DIR__ . '/../config/module.php');
    }

    /**
     * 
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }

}
