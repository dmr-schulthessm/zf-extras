<?php

namespace ZfExtra\ModuleManager\Listener;

use ReflectionClass;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\ModuleManager\ModuleEvent;

/**
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 * 
 * @property ModuleListenerManager $serviceLocator
 */
class TemplateStackResolver extends AbstractListenerAggregate
{

    /**
     * 
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(ModuleEvent::EVENT_MERGE_CONFIG, [$this, 'onMergeConfig'], 1000);
    }

    /**
     * 
     * @param ModuleEvent $event
     */
    public function onMergeConfig(ModuleEvent $event)
    {
        $config = $event->getConfigListener()->getMergedConfig(false);
        $modules = $event->getTarget()->getLoadedModules();
        foreach ($modules as $module) {
            $ref = new ReflectionClass($module);
            $dir = dirname($ref->getFileName()) . '/view';
            if (!is_dir($dir)) {
                continue;
            }

            if (!isset($config['view_manager']['template_path_stack'])) {
                $config['view_manager']['template_path_stack'] = array();
            }
            $config['view_manager']['template_path_stack'][] = $dir;
        }
        $event->getConfigListener()->setMergedConfig($config);
    }

}
