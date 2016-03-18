<?php

namespace ZfExtra\Log\EventListener;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\Log\Logger;
use Zend\Mvc\MvcEvent;
use ZfExtra\Log\EventListener\LogEventListener;
use ZfExtra\Log\LogEvent;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@muehlemann-popp.ch>
 */
class MvcEventListener extends AbstractListenerAggregate implements EventManagerAwareInterface
{
    use \Zend\EventManager\EventManagerAwareTrait;
    
    protected $eventIdentifier = LogEventListener::LOG_PROVIDER;


    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_BOOTSTRAP, [$this, 'onBootstrap']);
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'onRoute']);
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch']);
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, [$this, 'onDispatchError']);
    }

    public function onBootstrap(MvcEvent $event) 
    {
        /* @var $request Request */
        $request = $event->getRequest();
        $message = sprintf('%s: %s %s', __FUNCTION__, $request->getMethod(), $request->getServer('REQUEST_URI'));
        $this->getEventManager()->trigger(new LogEvent($this, $message, Logger::INFO));
    }

    public function onRoute(MvcEvent $event) 
    {
        $routeMatch = $event->getRouteMatch();
        $message = sprintf('%s: resolved route: %s', __FUNCTION__, $routeMatch->getMatchedRouteName());
        $this->getEventManager()->trigger(new LogEvent($this, $message, Logger::INFO));
    }

    public function onDispatch(MvcEvent $event) 
    {
        $routeMatch = $event->getRouteMatch();
        $params = $event->getRouteMatch()->getParams();
        unset($params['controller']);
        unset($params['action']);
        
        $strParams = [];
        foreach ($params as $name => $value) {
            $strParams[] = sprintf('%s = "%s", ', $name, $value);
        }
        $strParams = join($strParams);
        
        $message = sprintf('%s: dispatch target: "%s:%s": with params: [%s]', __FUNCTION__, $routeMatch->getParam('controller'), $routeMatch->getParam('action'), $strParams);
        $this->getEventManager()->trigger(new LogEvent($this, $message, Logger::INFO));
    }
}
