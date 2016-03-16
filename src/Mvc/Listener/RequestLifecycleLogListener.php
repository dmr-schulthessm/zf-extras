<?php

namespace ZfExtra\Mvc\Listener;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\Log\Logger;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use ZfExtra\Log\LogEvent;
use ZfExtra\Log\EventListener\LogEventListener;

class RequestLifecycleLogListener extends AbstractListenerAggregate implements EventManagerAwareInterface
{

    use EventManagerAwareTrait;
    use ServiceLocatorAwareTrait;

    protected $eventIdentifier = LogEventListener::LOG_PROVIDER;

    /**
     * 
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        if (PHP_SAPI != 'cli') {
            $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'onRoute'], 1100);
            $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 1100);
        }
    }

    public function onRoute(MvcEvent $event)
    {
        /* @var $request Request */
        $request = $event->getApplication()->getRequest();
        $this->getEventManager()->trigger(new LogEvent($this, 'onRoute: ' . $request->getRequestUri(), Logger::DEBUG));
    }

    public function onDispatch(MvcEvent $event)
    {
        /* @var $routeMatch RouteMatch */
        $routeMatch = $event->getRouteMatch();
        $this->getEventManager()->trigger(new LogEvent($this, 'oDispatch: matched route: ' . $routeMatch->getMatchedRouteName(), Logger::DEBUG));
    }

}
