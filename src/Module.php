<?php

namespace ZfExtra;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;
use ZfExtra\Config\Config;
use ZfExtra\Config\ConfigHelper;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{

    public function onBootstrap(MvcEvent $event)
    {
        $serviceManager = $event->getApplication()->getServiceManager();
        /* @var $config ConfigHelper */
        $config = $serviceManager->get('config.helper');
        $translatorCache = $serviceManager->get('mvctranslator')->getCache();
        if ($translatorCache) {
            $translatorCache->setCaching(!$config->getVariable('debug'));
        }
    }

    /**
     * 
     * @return array
     */
    public function getConfig()
    {
        return Config::load(__DIR__ . '/../config/module.yml');
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
