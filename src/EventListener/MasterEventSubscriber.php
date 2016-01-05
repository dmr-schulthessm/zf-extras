<?php

namespace ZfExtra\EventListener;

use Exception;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use ZfExtra\EventManager\EventsAwareInterface;
use ZfExtra\EventManager\EventsAwareTrait;

/**
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class MasterEventSubscriber extends AbstractListenerAggregate implements ServiceLocatorAwareInterface, EventsAwareInterface
{

    use ServiceLocatorAwareTrait;
    use EventsAwareTrait;

    /**
     * 
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_BOOTSTRAP, [$this, 'onBootstrap']);
    }

    /**
     * 
     * @param MvcEvent $event
     */
    public function onBootstrap(MvcEvent $event)
    {
        $appEvents = $this->serviceLocator->get('config_helper')->get('event_listeners');
        foreach ($appEvents as $listenerClass) {
            if (is_string($listenerClass)) {
                if ($this->serviceLocator->has($listenerClass)) {
                    $listener = $this->serviceLocator->get($listenerClass);
                } else if (class_exists($listenerClass)) {
                    $listener = new $listenerClass;
                } else {
                    throw new Exception('Unknown event listener: "' . $listener . '"');
                }
            }

            if ($listener instanceof ListenerAggregateInterface) {
                $this->events->attachAggregate($listener);
            } else {
                $this->events->attach($event, $callback);
            }
        }
    }

}
