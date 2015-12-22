<?php

namespace ZfExtra\Twig\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\Twig\Loader\PathStackLoader;

class PathStackLoaderFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        return new PathStackLoader($config['view_manager']['template_path_stack']);
    }

}
