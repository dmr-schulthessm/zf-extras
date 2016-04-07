<?php

namespace ZfExtra\Twig\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\Twig\View\TwigRenderer;
use ZfExtra\Twig\View\TwigStrategy;

class TwigStrategyFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new TwigStrategy($serviceLocator->get(TwigRenderer::class));
    }

}
