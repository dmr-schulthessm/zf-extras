<?php

namespace ZfExtra\EventListener;

use Zend\EventManager\SharedEventManagerInterface;

/**
 *
 * @author alex
 */
interface SharedEventListenerInterface
{

    public function attachShared(SharedEventManagerInterface $events);
}
