<?php

namespace ZfExtra\Mvc\Listener;

use Doctrine\ORM\EntityNotFoundException;
use ReflectionClass;
use Zend\Code\Scanner\FileScanner;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\ModuleManager\ModuleEvent;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Stdlib\ArrayUtils;
use ZfExtra\Mvc\DoctrineObjectInjector;

class DoctrineObjectInjectionListener extends AbstractListenerAggregate implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    /**
     * 
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(ModuleEvent::EVENT_MERGE_CONFIG, [$this, 'onMergeConfig'], -1);
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 1100);
    }

    /**
     * 
     * @param ModuleEvent $event
     */
    public function onMergeConfig(ModuleEvent $event)
    {
        $config = $event->getConfigListener()->getMergedConfig(false);

        $resolvedControllers = $this->resolveControllers($config);
        
        $controllers = array_merge($config['controllers']['invokables'], $config['controllers']['factories']);
        $injections = array();
        foreach ($controllers as $controller) {
            $ref = new ReflectionClass($controller);
            $fileScanner = new FileScanner($ref->getFileName());
            $classScanner = $fileScanner->getClass($controller);
            $methods = $classScanner->getMethods();
            $className = $classScanner->getName();
            $controllerName = $resolvedControllers[$className];
            foreach ($methods as $method) {
                $methodName = $method->getName();
                $actionName = preg_replace('/Action$/', '', $methodName);
                foreach ($method->getParameters() as $parameter) {
                    $parameterScanner = $method->getParameter($parameter);
                    $parameterName = $parameterScanner->getName();
                    $class = $parameterScanner->getClass();
                    if ($class) {
                        $objectManager = $this->detectObjectManager($class, $config['mvc']['doctrine_object_injector']);
                        if (null !== $objectManager) {
                            $injections[$controllerName][$actionName][$parameterName] = [$class, $objectManager];
                        }
                    } else {
                        $injections[$controllerName][$actionName][$parameterName] = null;
                    }
                }
            }
        }

        $config['mvc']['doctrine_object_injector']['injections'] = $injections;
        $event->getConfigListener()->setMergedConfig($config);
    }

    public function onDispatch(MvcEvent $event)
    {
        /* @var $routeMatch RouteMatch */
        $routeMatch = $event->getRouteMatch();
        
        /* @var $injector DoctrineObjectInjector */
        $injector = $this->serviceLocator->get('doctrine_object_injector');
        
        $keysToRemove = array('controller', 'action');
        $params = $routeMatch->getParams();
        foreach ($params as $key => $value) {
            if (in_array($key, $keysToRemove)) {
                unset($params[$key]);
            }
        }
        
        try {
            $arguments = $injector->makeArguments($routeMatch->getParam('controller'), $routeMatch->getParam('action'), $params);
            $event->setParam('__method_arguments', $arguments);
        } catch (EntityNotFoundException $e) {
            $event->getRouteMatch()->setParam('action', 'not-found');
            $event->setParam('__method_arguments', []);
        }
    }

    private function detectObjectManager($class, $config)
    {
        $ref = new ReflectionClass($class);
        foreach ($config['object_mapping'] as $objectManager => $targets) {
            foreach ($targets as $target) {
                if ($ref->isSubclassOf($target)) {
                    return $objectManager;
                }

                if ($ref->isInstantiable()) {
                    return $objectManager;
                }
            }
        }
    }

    public function resolveControllers(array $config)
    {
        $controllers = isset($config['controllers']) ? $config['controllers'] : array();
        $controllers['invokables'] = isset($controllers['invokables']) ? $controllers['invokables'] : array();
        $controllers['factories'] = isset($controllers['factories']) ? $controllers['factories'] : array();

        $controllers = ArrayUtils::merge($controllers['invokables'], $controllers['factories']);
        return array_flip($controllers);
    }
}
