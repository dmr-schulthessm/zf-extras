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
class TranslationResolver extends AbstractListenerAggregate
{

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
            $dir = dirname($ref->getFileName()) . '/language';
            if (!is_dir($dir)) {
                continue;
            }

            $iterator = new \DirectoryIterator($dir);
            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    list($domain, $locale, $type) = explode('.', $file->getFilename());
                    if ($type === 'php') {
                        $type = 'phparray';
                    }
                    $config['translator']['translation_files'][] = array(
                        'type' => $type,
                        'filename' => $file->getPathname(),
                        'text_domain' => $domain,
                        'locale' => $locale
                    );
                }
            }
        }
        
        $event->getConfigListener()->setMergedConfig($config);
    }

}
