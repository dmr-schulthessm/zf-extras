<?php

namespace ZfExtra;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Mvc\MvcEvent;
use ZfExtra\Config\Config;
use ZfExtra\View\LayoutSwitcherListener;

/**
 * 
 */
class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{

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
