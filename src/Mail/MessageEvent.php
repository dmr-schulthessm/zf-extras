<?php

namespace ZfExtra\Mail;

use Zend\EventManager\Event;

class MessageEvent extends Event
{
    const EVENT_PRE_SEND = 'mailer.pre-send';
    const EVENT_POST_SEND = 'mailer.post-send';
    
    /**
     *
     * @var Message
     */
    protected $message;

    /**
     * 
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * 
     * @param Message $message
     */
    public function setMessage(Message $message)
    {
        $this->message = $message;
    }

}
