<?php

namespace ZfExtra\Mvc\Controller\Plugin;

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
class Form extends AbstractPlugin implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    /**
     * Returns form instance.
     * 
     * @param string $name
     * @return mixed|ServiceManager
     */
    public function __invoke($name = null)
    {
        if (null == $name) {
            return $this->serviceLocator->getServiceLocator()->get('FormElementManager');
        } else {
            return $this->serviceLocator->getServiceLocator()->get('FormElementManager')->get($name);
        }
    }

}
