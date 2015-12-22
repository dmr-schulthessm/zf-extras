<?php

namespace ZfExtra\EventManager;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\EventManager\EventsAwareInterface;

/**
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class EventsInitializer implements InitializerInterface
{

    /**
     * 
     * @param EventsAwareInterface $instance
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        if ($instance instanceof EventsAwareInterface) {
            if ($serviceLocator instanceof AbstractPluginManager) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            $instance->setEventManager($serviceLocator->get('events'));
        }
    }

}
