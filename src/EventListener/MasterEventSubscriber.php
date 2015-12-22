<?php

namespace ZfExtra\EventListener;

use Exception;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
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
        foreach ($appEvents as $listenerConfig) {
            if (is_string($listenerConfig)) {
                if ($this->serviceLocator->has($listenerConfig)) {
                    $listener = $this->serviceLocator->get($listenerConfig);
                } else if (class_exists($listenerConfig)) {
                    $listener = new $listener;
                } else {
                    throw new Exception('Unknown event listener: "' . $listener . '"');
                }
            }

            $this->events->attachAggregate($listener);
        }
    }

}
