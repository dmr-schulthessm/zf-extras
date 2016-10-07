<?php

namespace ZfExtra\Mail;

use Exception;
use Zend\Mail\Address;
use Zend\Mail\AddressList;

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
        $message = new Message($draft->getFrom(), $draft->getTo(), $draft->getSubject(), $draft->getBody(), $draft->getType());
        
        $message->setCc($this->applyEmails($draft->getCc()));
        $message->setBcc($this->applyEmails($draft->getBcc()));
        return $message;
    }
    
    /**
     * 
     * @param array $emails
     * @return AddressList
     */
    private function applyEmails(array $emails)
    {
        $addressList = new AddressList;
        
        foreach ($emails as $email => $name) {
            if (is_string($email)) {
                $address = new Address($email, $name);
            } else {
                $address = new Address($name);
            }
            $addressList->add($address);
        }
        return $addressList;
    }

}
