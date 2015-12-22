<?php

namespace ZfExtra\Console;

use Symfony\Component\Console\Command\Command;
use Zend\ServiceManager\AbstractPluginManager;

class CommandManager extends AbstractPluginManager
{

    public function validatePlugin($plugin)
    {
        return $plugin instanceof Command;
    }

}
