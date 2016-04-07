<?php

namespace ZfExtra\Twig\Service;

use Twig_Environment;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\Twig\View\TwigResolver;

class TwigResolverFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new TwigResolver($serviceLocator->get(Twig_Environment::class));
    }

}
