<?php

namespace ZfExtra\ModuleManager\Listener;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\ModuleManager\ModuleEvent;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Stdlib\ArrayUtils;
use ZfExtra\Config\Config;
use ZfExtra\ModuleManager\ModuleListenerManager;

/**
 * Merges framework and application config.
 * 
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 * 
 * @property ModuleListenerManager $serviceLocator
 */
class ConfigMergerListener extends AbstractListenerAggregate implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    /**
     * 
     * @param EventManagerInterface $events
     * @return InterpolateListener
     */
    public function attach(EventManagerInterface $events)
    {
        $this->callbacks[] = $events->attach(ModuleEvent::EVENT_MERGE_CONFIG, [$this, 'onLoadModulesPost'], 200);
    }

    /**
     * Interpolate variables.
     * 
     * @param ModuleEvent $e
     */
    public function onLoadModulesPost(ModuleEvent $e)
    {
        $appConfig = $this->serviceLocator->getServiceLocator()->get('ApplicationConfig');
        $extraConfig = array();
        if (isset($appConfig['framework']['app_config'])) {
            $extraConfig = Config::load($appConfig['framework']['app_config']);
        }

        $config = ArrayUtils::merge($e->getConfigListener()->getMergedConfig(false), $extraConfig);
        $e->getConfigListener()->setMergedConfig($config);
    }

}
