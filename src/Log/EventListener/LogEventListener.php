<?php

namespace ZfExtra\Log\EventListener;

use Zend\EventManager\EventInterface;
use Zend\EventManager\SharedEventManagerInterface;
use Zend\Log\Logger;
use ZfExtra\EventListener\SharedEventListenerInterface;
use ZfExtra\Log\LogEvent;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@muehlemann-popp.ch>
 */
class LogEventListener implements SharedEventListenerInterface
{

    const LOG_PROVIDER = 'app.log';

    /**
     *
     * @var Logger
     */
    protected $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function attachShared(SharedEventManagerInterface $events)
    {
        $events->attach(self::LOG_PROVIDER, '*', [$this, 'onEvent']);
    }

    public function onEvent(EventInterface $event)
    {
        if ($event instanceof LogEvent) {
            $this->logger->log($event->getPriority(), get_class($event->getTarget()) . ': ' . $event->getMessage(), $event->getParams());
        }
    }

}
