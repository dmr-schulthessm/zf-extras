<?php

namespace ZfExtra\Twig\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\Twig\Loader\TemplateMapLoader;

class TemplateMapLoaderFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $paths = [];
        if (isset($config['view_manager']['template_map'])) {
            $paths = $config['view_manager']['template_map'];
        }
        return new TemplateMapLoader($paths);
    }

}
