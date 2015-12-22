<?php

namespace ZfExtra\Assertion;

use Zend\ServiceManager\AbstractPluginManager;

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
     * 
     * @param string $controller
     * @param string $action
     * @return AssertionInterface[]
     */
    public function find($controller, $action)
    {
        $config = $this->getServiceLocator()->get('config.helper');
        $assertions = array();
        $configured = $config->get(sprintf('asserts.%s.%s', $controller, $action), array());
        foreach ($configured as $assertion) {
            $assertions[] = $this->get($assertion);
        }
        return $assertions;
    }
}
