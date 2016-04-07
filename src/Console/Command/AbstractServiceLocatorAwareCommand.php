<?php

namespace ZfExtra\Console\Command;

use Symfony\Component\Console\Command\Command;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use ZfExtra\Console\CommandManager;

/**
 * @property CommandManager $serviceLocator
 */
abstract class AbstractServiceLocatorAwareCommand extends Command implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;
}
