<?php

namespace ZfExtra\Mail\Listener;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use ZfExtra\Mail\MessageEvent;

/**
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class BodySetterListener extends AbstractListenerAggregate
{

    /**
     * 
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MessageEvent::EVENT_PRE_SEND, [$this, 'onPreSend']);
    }

    /**
     * 
     * @param MessageEvent $event
     */
    public function onPreSend(MessageEvent $event)
    {
        $event->getTarget()->prepare();
    }

}
