<?php

namespace ZfExtra\Assertion\Listener;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Filter\Word\DashToCamelCase;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class AssertionListener extends AbstractListenerAggregate
{

    use ServiceLocatorAwareTrait;

    /**
     * 
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 1000);
    }

    /**
     * 
     * @param MvcEvent $event
     */
    public function onDispatch(MvcEvent $event)
    {
        $filter = new DashToCamelCase;
        $assertionManager = $this->serviceLocator->get('AssertionManager');
        $routeMatch = $event->getRouteMatch();
        $controller = $routeMatch->getParam('controller');
        $action = lcfirst($filter->filter($routeMatch->getParam('action')));
        $assertsConfig = $assertionManager->findConfig($controller, $action);

        foreach ($assertsConfig as $config) {
            $assert = $assertionManager->get($config['assert']);
            if (!$assert->test($event, $config['options'])) {
                return call_user_func_array([$assert, 'onFail'], [$event]);
            }
        }
    }

}
