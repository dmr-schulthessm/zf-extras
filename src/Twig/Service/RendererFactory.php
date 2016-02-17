<?php

namespace ZfExtra\Twig\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\Twig\View\TwigRenderer;

class RendererFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $phpRenderer = $serviceLocator->get('ViewRenderer');
        
        return new TwigRenderer(
            $serviceLocator->get('Zend\View\View'),
            $serviceLocator->get('twig.loader'),
            $serviceLocator->get('twig.environment'),
            $serviceLocator->get('twig.resolver'),
            $phpRenderer,
            $serviceLocator->get('config.helper')->get('view_manager.layout_inheritance')
        );
    }

}
