<?php

namespace ZfExtra\Assertion\Listener;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class AssertionListener extends AbstractListenerAggregate implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    /**
     * 
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
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
        $asserts = $assertionManager->find($event->getRouteMatch()->getParam('controller'), $event->getRouteMatch()->getParam('action'));
        foreach ($asserts as $assert) {
            if (!$assert->test($event)) {
                return call_user_func_array([$assert, 'onFail'], array($event));
            }
        }
    }

}
