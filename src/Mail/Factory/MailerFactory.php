<?php

namespace ZfExtra\Mail\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\Config\ConfigHelper;
use ZfExtra\Mail\Mailer;
use ZfExtra\Mail\MessageFactory;

/**
 * Mailer factory.
 * 
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class MailerFactory implements FactoryInterface
{

    /**
     * Factory.
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @return Mailer
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $mailer = new Mailer($serviceLocator->get(ConfigHelper::class)->get('mailer'));
        $mailer->setMessageFactory($serviceLocator->get(MessageFactory::class));
        return $mailer;
    }

}
