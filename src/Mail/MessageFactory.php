<?php

namespace ZfExtra\Mail;

use Exception;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@muehlemann-popp.ch>
 */
class MessageFactory
{

    /**
     *
     * @var array
     */
    protected $config;

    /**
     * 
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * 
     * @param string $name
     * @return Message
     * @throws Exception
     */
    public function create($name)
    {
        if (!isset($this->config[$name])) {
            throw new Exception(__METHOD__ . ': message draft with name "' . $name . '" does not exists.');
        }

        $draft = new MessageDraft($this->config[$name]);
        return new Message($draft->getFrom(), $draft->getTo(), $draft->getSubject(), $draft->getBody(), $draft->getType());
    }

}
