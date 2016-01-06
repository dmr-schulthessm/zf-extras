<?php

namespace ZfExtra\Mail;

use Exception;
use Zend\Mail\Transport\TransportInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use ZfExtra\EventManager\EventsAwareInterface;
use ZfExtra\EventManager\EventsAwareTrait;

/**
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class Mailer implements ServiceLocatorAwareInterface, EventsAwareInterface
{

    use ServiceLocatorAwareTrait;
    use EventsAwareTrait;

    /**
     * @var TransportInterface
     */
    protected $transport;

    /**
     * 
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->setOptions($options);
    }

    /**
     * 
     * @param array $options
     * @throws Exception
     */
    protected function setOptions(array $options)
    {
        if (!isset($options['transport']['name'])) {
            throw new Exception('No mail transport.');
        }

        $this->transport = new $options['transport']['name'];
        if (!$this->transport instanceof TransportInterface) {
            throw new Exception('Transport must implement Zend\Mail\Transport\TransportInterface');
        }
        $transportOptions = $this->transport->getOptions();
        $transportOptions->setFromArray($options['transport']['options']);
        $this->transport->setOptions($transportOptions);
    }

    /**
     * 
     * @param Message $message
     */
    public function send(Message $message)
    {
        $mailMessage = MessageConverter::convert($message);
        $this->events->trigger(new MessageEvent(MessageEvent::EVENT_PRE_SEND, $message, ['mail_message' => $mailMessage]));
        $this->transport->send($mailMessage);
        $this->events->trigger(new MessageEvent(MessageEvent::EVENT_POST_SEND, $message, ['mail_message' => $mailMessage]));
    }
    
    /**
     * 
     * @return TransportInterface
     */
    public function getTransport()
    {
        return $this->transport;
    }

}
