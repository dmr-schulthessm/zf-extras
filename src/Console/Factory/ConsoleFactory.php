<?php

namespace ZfExtra\Console\Factory;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;
use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\Config\ConfigHelper;
use ZfExtra\Console\CommandManager;

class ConsoleFactory implements FactoryInterface
{

    /**
     *
     * @var EventManagerInterface
     */
    protected $events;

    /**
     * 
     * @param ServiceLocatorInterface $sm
     * @return EventManagerInterface
     */
    public function getEventManager(ServiceLocatorInterface $sm)
    {
        if (null === $this->events) {
            /* @var $events EventManagerInterface */
            $events = $sm->get('EventManager');
            $events->addIdentifiers(array(
                __CLASS__,
                'console',
                'doctrine'
            ));
            $this->events = $events;
        }
        return $this->events;
    }

    /**
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @return Application
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get(ConfigHelper::class);
        
        $commandManager = $serviceLocator->get('CommandManager');
        $commands = array_values($commandManager->getCanonicalNames());
        $application = new Application($config->get('application.name'), $config->get('application.version'));

        foreach ($commands as $command) {
            $application->add($commandManager->get($command));
        }
        
        $application->setHelperSet(new HelperSet);
        $application->setCatchExceptions(true);
        $application->setAutoExit(false);

        $this->getEventManager($serviceLocator)->trigger('loadCli.post', $application, array('ServiceManager' => $serviceLocator));

        return $application;
    }

}
