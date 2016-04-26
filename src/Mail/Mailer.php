<?php

namespace ZfExtra\Mail;

use Exception;
use Zend\Mail\Transport\TransportInterface;

/**
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class Mailer
{

    /**
     * @var TransportInterface
     */
    protected $transport;

    /**
     *
     * @var MessageFactory
     */
    protected $messageFactory;

    /**
     *
     * @var PluginInterface[] 
     */
    protected $plugins = [];


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

        if (method_exists($this->transport, 'getOptions')) {
            $transportOptions = $this->transport->getOptions();
            $transportOptions->setFromArray($options['transport']['options']);
            $this->transport->setOptions($transportOptions);
        }
    }

    /**
     * 
     * @param Message $message
     */
    public function send(Message $message)
    {
        $mailMessage = MessageConverter::convert($message);
        
        foreach ($this->plugins as $plugin) {
            $plugin->preSend($mailMessage);
        }
        
        $this->transport->send($mailMessage);
        
        foreach ($this->plugins as $plugin) {
            $plugin->preSend($mailMessage);
        }
    }

    /**
     * 
     * @return TransportInterface
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * 
     * @return MessageFactory
     */
    public function getMessageFactory()
    {
        return $this->messageFactory;
    }

    /**
     * 
     * @param MessageFactory $messageFactory
     * @return Mailer
     */
    public function setMessageFactory(MessageFactory $messageFactory)
    {
        $this->messageFactory = $messageFactory;
        return $this;
    }
    
    /**
     * 
     * @param PluginInterface $plugin
     */
    public function addPlugin(PluginInterface $plugin)
    {
        $this->plugins[] = $plugin;
    }

}
