<?php

namespace ZfExtra\Assertion\Listener;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Stdlib\ArrayUtils;
use ZfAnnotation\Event\ParseEvent;
use ZfExtra\Assertion\Annotation\Assert;

class AnnotationListener extends AbstractListenerAggregate
{
    /**
     *
     * @var array
     */
    protected $controllers = array();
    
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(ParseEvent::EVENT_CLASS_PARSED, [$this, 'onClassParsed']);
    }
    
    public function onClassParsed(ParseEvent $event)
    {
        $this->resolveControllers($event->getParam('config'));
        
        $classHolder = $event->getTarget();
        $methodHolders = $classHolder->getMethods();
        $config = array();
        foreach ($methodHolders as $methodHolder) {
            foreach ($methodHolder->getAnnotations() as $annotation) {
                if ($annotation instanceof Assert) {
                    $controller = $classHolder->getClass()->getName();
                    if (isset($this->controllers[$controller])) {
                        $controller = $this->controllers[$controller];
                    }
                    $method = preg_replace('/Action$/', '', $methodHolder->getMethod()->getName());
                    $config['asserts'][$controller][$method][] = $annotation->getName();
                }
            }
        }
        $event->mergeResult($config);
    }
    
    public function resolveControllers(array $config)
    {
        $controllers = isset($config['controllers']) ? $config['controllers'] : array();
        $controllers['invokables'] = isset($controllers['invokables']) ? $controllers['invokables'] : array();
        $controllers['factories'] = isset($controllers['factories']) ? $controllers['factories'] : array();

        $controllers = ArrayUtils::merge($controllers['invokables'], $controllers['factories']);
        $this->controllers = array_flip($controllers);
    }

}
