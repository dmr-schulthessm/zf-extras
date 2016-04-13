<?php

namespace ZfExtra\Assertion;

use Zend\ServiceManager\AbstractPluginManager;
use ZfExtra\Config\ConfigHelper;

class AssertionManager extends AbstractPluginManager
{

    /**
     * 
     * @param AssertionInterface $plugin
     * @return bool
     */
    public function validatePlugin($plugin)
    {
        return $plugin instanceof AssertionInterface;
    }

    /**
     * Returns assertions.
     * 
     * @param string $controller
     * @param string $action
     * @return AssertionInterface[]
     */
    public function find($controller, $action)
    {
        $configured = $this->findConfig($controller, $action);
        foreach ($configured as $assertion) {
            $instance = $this->get($assertion['assert']);
            $instance->setOptions($assertion['options']);
            $assertions[] = $instance;
        }
        return $assertions;
    }
    
    /**
     * Returns assertion config array.
     * 
     * @param string $controller
     * @param string $action
     * @return array
     */
    public function findConfig($controller, $action)
    {
        $config = $this->getServiceLocator()->get(ConfigHelper::class);
//        var_dump(sprintf('asserts.%s.%s', $controller, strtolower($action)));
        return $config->get(sprintf('asserts.%s.%s', $controller, $action), array());
    }
}
