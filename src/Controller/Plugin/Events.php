<?php

namespace ZfExtra\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use ZfExtra\EventManager\EventsAwareInterface;
use ZfExtra\EventManager\EventsAwareTrait;

/**
 * Application event manager.
 * 
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class Events extends AbstractPlugin implements EventsAwareInterface
{

    use EventsAwareTrait;

    /**
     * Returns app's event manager.
     * @return string
     */
    public function __invoke()
    {
        return $this->events;
    }

}
