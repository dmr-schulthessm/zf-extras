<?php

namespace ZfExtra\Mvc\Listener;

use ReflectionClass;
use Zend\Code\Scanner\FileScanner;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\ModuleManager\ModuleEvent;

class DoctrineObjectInjectionListener extends AbstractListenerAggregate
{

    /**
     * 
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(ModuleEvent::EVENT_MERGE_CONFIG, [$this, 'onMergeConfig'], -1);
    }

    /**
     * 
     * @param ModuleEvent $event
     */
    public function onMergeConfig(ModuleEvent $event)
    {
        $config = $event->getConfigListener()->getMergedConfig(false);

        $controllers = array_merge($config['controllers']['invokables'], $config['controllers']['factories']);
        $injections = array();
        foreach ($controllers as $controller) {
            $ref = new ReflectionClass($controller);
            $fileScanner = new FileScanner($ref->getFileName());
            $classScanner = $fileScanner->getClass($controller);
            $methods = $classScanner->getMethods();
            $className = $classScanner->getName();
            foreach ($methods as $method) {
                $methodName = $method->getName();
                foreach ($method->getParameters() as $parameter) {
                    $parameterScanner = $method->getParameter($parameter);
                    $parameterName = $parameterScanner->getName();
                    $class = $parameterScanner->getClass();
                    if ($class) {
                        $objectManager = $this->detectObjectManager($class, $config['mvc']['doctrine_object_injector']);
                        if (null !== $objectManager) {
                            $injections[$className][$methodName][$parameterName] = [$class, $objectManager];
                        }
                    } else {
                        $injections[$className][$methodName][$parameterName] = null;
                    }
                }
            }
        }

        $config['mvc']['doctrine_object_injector']['injections'] = $injections;
        $event->getConfigListener()->setMergedConfig($config);
    }

    private function detectObjectManager($class, $config)
    {
        $ref = new ReflectionClass($class);
        foreach ($config['object_mapping'] as $objectManager => $targets) {
            foreach ($targets as $target) {
                if ($ref->isSubclassOf($target) || $ref->isInstance(new $target)) {
                    return $objectManager;
                }
            }
        }
    }

}
