<?php

namespace ZfExtra\Acl\Annotation;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Stdlib\ArrayUtils;
use ZfAnnotation\Event\ParseEvent;
use ZfExtra\Acl\Annotation\Acl;

class AnnotationListener extends AbstractListenerAggregate
{

    /**
     *
     * @var array
     */
    protected $controllers = array();

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(ParseEvent::EVENT_CLASS_PARSED, [$this, 'onClassParsed']);
    }

    public function onClassParsed(ParseEvent $event)
    {
        $this->resolveControllers($event->getParam('config'));
        $config = array();

        $classHolder = $event->getTarget();
        $controller = $classHolder->getClass()->getName();
        if (isset($this->controllers[$controller])) {
            $controller = $this->controllers[$controller];
        }

        foreach ($classHolder->getAnnotations() as $classAnnotation) {
            if ($classAnnotation instanceof Acl) {
                $allow = $classAnnotation->getAllow();
                $deny = $classAnnotation->getDeny();

                if ($allow) {
                    $config['acl']['resources']['allow'][$controller]['*'] = $allow;
                }

                if ($deny) {
                    $config['acl']['resources']['deny'][$controller]['*'] = $deny;
                }
            }
        }

        $methodHolders = $classHolder->getMethods();
        foreach ($methodHolders as $methodHolder) {
            foreach ($methodHolder->getAnnotations() as $annotation) {
                if ($annotation instanceof Acl) {
                    $method = preg_replace('/Action$/', '', $methodHolder->getMethod()->getName());

                    $allow = $annotation->getAllow();
                    $deny = $annotation->getDeny();

                    if ($allow) {
                        $config['acl']['resources']['allow'][$controller][$method] = $allow;
                    }

                    if ($deny) {
                        $config['acl']['resources']['deny'][$controller][$method] = $deny;
                    }
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
