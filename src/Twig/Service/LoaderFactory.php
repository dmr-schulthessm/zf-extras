<?php

namespace ZfExtra\Twig\Service;

use Twig_Loader_Chain;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoaderFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $loaders = array();
        foreach ($config['twig']['loaders'] as $twigLoader) {
            $loaders[] = $serviceLocator->get($twigLoader);
        }
        return new Twig_Loader_Chain($loaders);
    }

}
