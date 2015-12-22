<?php

namespace ZfExtra\EventManager;

use Zend\EventManager\EventManagerInterface;

/**
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
trait EventsAwareTrait
{

    /**
     * @var EventManagerInterface
     */
    protected $events;

    /**
     * 
     * @param EventManagerInterface $events
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $this->events = $events;
    }

    /**
     * 
     * @return EventManager
     */
    public function getEventManager()
    {
        return $this->events;
    }

}
