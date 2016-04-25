<?php

namespace ZfExtra\Mvc\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\Controller\PluginManager;
use Zend\Mvc\I18n\Translator;
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
    public function __invoke($message = null, $textDomain = 'default', $locale = null)
    {
        if (func_num_args() === 0) {
            return $this->getTranslator();
        } else {
            return $this->getTranslator()->translate($message, $textDomain, $locale);
        }
    }
    
    /**
     * 
     * @return Translator
     */
    public function getTranslator()
    {
        return $this->serviceLocator->getServiceLocator()->get('mvctranslator');
    }

}
