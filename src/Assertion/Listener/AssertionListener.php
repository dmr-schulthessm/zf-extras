<?php

namespace ZfExtra\Assertion\Listener;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
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
        $assertionManager = $this->serviceLocator->get('AssertionManager');
        $routeMatch = $event->getRouteMatch();
        $assertConfig = $assertionManager->findConfig($routeMatch->getParam('controller'), $routeMatch->getParam('action'));
        foreach ($assertConfig as $config) {
            $assert = $assertionManager->get($config['assert']);
            if (!$assert->test($event, $config['options'])) {
                return call_user_func_array([$assert, 'onFail'], [$event]);
            }
        }
    }

}
