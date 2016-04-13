<?php

namespace ZfExtra;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;
use ZfExtra\Config\Config;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{

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
