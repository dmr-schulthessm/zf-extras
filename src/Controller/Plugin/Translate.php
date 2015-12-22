<?php

namespace ZfExtra\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\Controller\PluginManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Provides access to translator from controller.
 * 
 * @property PluginManager $serviceLocator Controller plugin manager.
 * 
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class Translate extends AbstractPlugin implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    /**
     * Translate a message.
     * 
     * @param string $message
     * @param string $textDomain
     * @param string $locale
     * @return string
     */
    public function __invoke($message, $textDomain = 'default', $locale = null)
    {
        return $this->serviceLocator->getServiceLocator()->get('mvctranslator')->translate($message, $textDomain, $locale);
    }

}
