<?php

namespace ZfExtra\Command;

use Symfony\Component\Console\Command\Command;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use ZfExtra\Console\CommandManager;

abstract class AbstractServiceLocatorAwareCommand extends Command implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;
    
    /**
     *
     * @var CommandManager
     */
    protected $serviceLocator;
}
