<?php

namespace ZfExtra\ModuleManager\Listener;

use ReflectionClass;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\ModuleManager\ModuleEvent;
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
     * @var array
     */
    protected $ignoreModules = [];
    
    /**
     * 
     * @param array $ignoredModules
     */
    public function __construct(array $ignoredModules = [])
    {
        $this->ignoreModules = $ignoredModules;
    }
    
    /**
     * 
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events, $priority = 1)
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
        foreach ($modules as $module) {
            $ref = new ReflectionClass($module);
            $dir1 = dirname($ref->getFileName()) . '/Entity';
            $dir2 = dirname($ref->getFileName()) . '/src/Entity';
            
            $dirs = array_filter([$dir1, $dir2], 'is_dir');
            if (count($dirs) == 0) {
                continue;
            }
            
            $dir = current($dirs);

            $parts = explode('\\', get_class($module));
            $moduleName = array_shift($parts);
            if (in_array($moduleName, $this->ignoreModules)) {
                continue;
            }
            $driverName = strtolower($moduleName) . '_entities';

            $config['doctrine']['driver']['orm_default']['drivers'][sprintf('%s\Entity', $moduleName)] = $driverName;
            $config['doctrine']['driver'][$driverName] = array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array($dir)
            );
        }
        
        $event->getConfigListener()->setMergedConfig($config);
    }

}
