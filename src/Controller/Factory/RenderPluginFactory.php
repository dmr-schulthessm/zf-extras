<?php

namespace ZfExtra\Controller\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\Controller\Plugin\Render;
use ZfExtra\View\DirectRenderer;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@muehlemann-popp.ch>
 */
class RenderPluginFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Render($serviceLocator->getServiceLocator()->get(DirectRenderer::class));
    }

}
