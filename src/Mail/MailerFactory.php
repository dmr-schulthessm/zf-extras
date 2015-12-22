<?php

namespace ZfExtra\Mail;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

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
     * @return \ZfExtra\Mail\Mailer
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Mailer($serviceLocator->get('config_helper')->get('mailer'));
    }

}
