<?php

namespace ZfExtra\Assertion\Listener;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Stdlib\ArrayUtils;
use ZfAnnotation\Event\ParseEvent;
use ZfExtra\Assertion\Annotation\Assert;
use ZfExtra\Assertion\Annotation\Asserts;

class AnnotationListener extends AbstractListenerAggregate
{

    /**
     *
     * @var array
     */
    protected $controllers = [];

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(ParseEvent::EVENT_CLASS_PARSED, [$this, 'onClassParsed']);
    }

    public function onClassParsed(ParseEvent $event)
    {
        $this->resolveControllers($event->getParam('config'));

        $classHolder = $event->getTarget();
        $methodHolders = $classHolder->getMethods();
        $config = [];
        foreach ($methodHolders as $methodHolder) {
            foreach ($methodHolder->getAnnotations() as $annotation) {
                $controller = $classHolder->getClass()->getName();
                if (isset($this->controllers[$controller])) {
                    $controller = $this->controllers[$controller];
                }
                $method = preg_replace('/Action$/', '', $methodHolder->getMethod()->getName());
                if ($annotation instanceof Asserts) {
                    foreach ($annotation->getNames() as $name) {
                        $options = empty($annotation->getOptions()[$name]) ? [] : $annotation->getOptions()[$name];
                        $config['asserts'][$controller][$method][] = [
                            'assert' => $name,
                            'options' => $options
                        ];
                    }
                }

                if ($annotation instanceof Assert) {
                    $config['asserts'][$controller][$method][] = [
                        'assert' => $annotation->getName(),
                        'options' => $annotation->getOptions()
                    ];
                }
            }
        }
        $event->mergeResult($config);
    }

    public function resolveControllers(array $config)
    {
        $controllers = isset($config['controllers']) ? $config['controllers'] : [];
        $controllers['invokables'] = isset($controllers['invokables']) ? $controllers['invokables'] : [];
        $controllers['factories'] = isset($controllers['factories']) ? $controllers['factories'] : [];

        $controllers = ArrayUtils::merge($controllers['invokables'], $controllers['factories']);
        $this->controllers = array_flip($controllers);
    }

}
