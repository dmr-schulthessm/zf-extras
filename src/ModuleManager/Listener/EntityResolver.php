<?php

namespace ZfExtra\ModuleManager\Listener;

use ReflectionClass;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\ModuleManager\ModuleEvent;
use Zend\Stdlib\ArrayUtils;
use ZfExtra\ModuleManager\ModuleListenerManager;

/**
 * @property ModuleListenerManager $serviceLocator
 * 
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class EntityResolver extends AbstractListenerAggregate
{

    /**
     * 
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(ModuleEvent::EVENT_MERGE_CONFIG, [$this, 'onMergeConfig']);
    }

    /**
     * 
     * @param ModuleEvent $event
     */
    public function onMergeConfig(ModuleEvent $event)
    {
        $config = $event->getConfigListener()->getMergedConfig(false);
        $modules = $event->getTarget()->getLoadedModules();
        $newConfig = array();
        foreach ($modules as $module) {
            $ref = new ReflectionClass($module);
            $dir = dirname($ref->getFileName()) . '/src/Entity';
            if (!is_dir($dir)) {
                continue;
            }

            $parts = explode('\\', get_class($module));
            $moduleName = array_shift($parts);
            $driverName = strtolower($moduleName) . '_entities';

//            $newConfig = ArrayUtils::merge($newConfig, array(
//                'doctrine' => array(
//                    'driver' => array(
//                        'orm_default' => array(
//                            'drivers' => array(
//                                sprintf('%s\Entity', $moduleName) => $driverName
//                            )
//                        ),
//                        $driverName => array(
//                            'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
//                            'cache' => 'array',
//                            'paths' => array($dir)
//                        )
//                    )
//                )
//            ));
            
            $config['doctrine']['driver']['orm_default']['drivers'][sprintf('%s\Entity', $moduleName)] = $driverName;
            $config['doctrine']['driver'][$driverName] = array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(0 => $dir)
            );
        }
        
//        var_dump(ArrayUtils::merge($config, $newConfig)['doctrine']['driver']);die;
        $event->getConfigListener()->setMergedConfig($config);
    }

}
