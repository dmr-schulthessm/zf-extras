<?php

namespace ZfExtra\Twig\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\Twig\DirectRenderer;

class TwigDirectRendererFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new DirectRenderer($serviceLocator->get('twig.renderer'));
    }

}
