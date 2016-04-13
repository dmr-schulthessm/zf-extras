<?php

namespace ZfExtra\Mail\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\Config\ConfigHelper;
use ZfExtra\Mail\Mailer;
use ZfExtra\Mail\MessageFactory;

/**
 * 
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class MessageFactoryFactory implements FactoryInterface
{

    /**
     * Factory.
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @return Mailer
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get(ConfigHelper::class)->get('mailer.messages');
        return new MessageFactory($config);
    }

}
