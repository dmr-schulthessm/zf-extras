<?php

namespace ZfExtra;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use ZfExtra\Config\Config;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{

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
