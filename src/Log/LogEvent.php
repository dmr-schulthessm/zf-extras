<?php

namespace ZfExtra\Log;

use Zend\EventManager\Event;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@muehlemann-popp.ch>
 */
class LogEvent extends Event
{

    /**
     *
     * @var string
     */
    protected $message;

    /**
     *
     * @var int
     */
    protected $priority;

    /**
     *
     * @var array
     */
    protected $extras = [];

    /**
     * 
     * @param object $target
     * @param string $message
     * @param int $priority
     * @param array $extras
     */
    public function __construct($target, $message, $priority, array $extras = [])
    {
        $this->message = $message;
        $this->priority = $priority;
        $this->extras = $extras;

        parent::__construct(__CLASS__, $target);
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

}
