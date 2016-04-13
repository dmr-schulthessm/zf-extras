<?php

namespace ZfExtra\Twig\Service;

use Twig_Environment;
use Twig_LoaderInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\View;
use ZfExtra\Config\ConfigHelper;
use ZfExtra\Twig\View\HelperPluginManager;
use ZfExtra\Twig\View\TwigRenderer;
use ZfExtra\Twig\View\TwigResolver;

class RendererFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $phpRenderer = $serviceLocator->get('ViewRenderer');
        
        $renderer = new TwigRenderer(
            $serviceLocator->get(View::class),
            $serviceLocator->get(Twig_LoaderInterface::class),
            $serviceLocator->get(Twig_Environment::class),
            $serviceLocator->get(TwigResolver::class),
            $phpRenderer,
            $serviceLocator->get(ConfigHelper::class)->get('view_manager.layout_inheritance')
        );
        $renderer->setHelperPluginManager($serviceLocator->get(HelperPluginManager::class));
        return $renderer;
    }

}
