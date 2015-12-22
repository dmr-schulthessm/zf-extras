<?php

namespace ZfExtra\Twig\Service;

use Twig_Loader_Filesystem;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FilesystemLoaderFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        return new Twig_Loader_Filesystem($config['view_manager']['template_path_stack']);
    }

}
