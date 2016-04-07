<?php

namespace ZfExtra\EventListener;

use Exception;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;
use ZfExtra\ModuleManager\ModuleListenerManager;

/**
 * @property ModuleListenerManager $serviceLocator
 * 
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class SharedEventsListener extends AbstractListenerAggregate
{

    /**
     * 
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_BOOTSTRAP, [$this, 'onBootstrap']);
    }

    /**
     * 
     * @param MvcEvent $event
     */
    public function onBootstrap(MvcEvent $event)
    {
        /* @var $sm ServiceManager */
        $sm = $event->getApplication()->getServiceManager();
        $config = $sm->get('Config');
        if (!isset($config['shared_event_listeners'])) {
            $config['shared_event_listeners'] = [];
        }

        foreach ($config['shared_event_listeners'] as $sharedEventListener) {
            if ($sm->has($sharedEventListener)) {
                $sharedEventListener = $sm->get($sharedEventListener);
            } else {
                $sharedEventListener = new $sharedEventListener;
            }
            if (!$sharedEventListener instanceof SharedEventListenerInterface) {
                throw new Exception(sprintf('Class "%s" must implement "%s" in order to subscribe shared events.', get_class($sharedEventListener), SharedEventListenerInterface::class));
            }
            
            $sharedEventListener->attachShared($sm->get('SharedEventManager'));
        }

    }

}
