<?php

namespace ZfExtra\Intl\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\Intl\IntlTwigExtension;

class IntlTwigExtensionFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new IntlTwigExtension($serviceLocator->get('mvctranslator'));
    }

}
