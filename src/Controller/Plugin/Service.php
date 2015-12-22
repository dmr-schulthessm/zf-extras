<?php

namespace ZfExtra\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\Controller\PluginManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceManager;

/**
 * @property PluginManager $serviceLocator Controller plugin manager.
 * 
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class Service extends AbstractPlugin implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    /**
     * Returns registered service instance.
     * 
     * @param string $name
     * @return mixed|ServiceManager
     */
    public function __invoke($name = null)
    {
        if (null == $name) {
            return $this->serviceLocator->getServiceLocator();
        } else {
            return $this->serviceLocator->getServiceLocator()->get($name);
        }
    }

}
