<?php
namespace ZfExtra\ModuleManager;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\ServiceManager\AbstractPluginManager;

class ModuleListenerManager extends AbstractPluginManager
{
    public function validatePlugin($plugin)
    {
        return $plugin instanceof ListenerAggregateInterface;
    }

}
