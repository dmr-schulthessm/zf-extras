<?php

namespace ZfExtra\Twig\Service;

use Twig_Environment;
use Twig_SimpleFunction;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TwigFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $viewHelperManager = $serviceLocator->get('ViewHelperManager');
        $phpRenderer = $serviceLocator->get('ViewRenderer');

        $config = $serviceLocator->get('Config');
        $env = new Twig_Environment($serviceLocator->get('twig.loader'), $config['twig']['options']);

        foreach ($config['twig']['extensions'] as $extension) {
            $enable = true;
            if (is_array($extension)) {
                list($extension, $enable) = $extension;
            }

            if (!$enable) {
                continue;
            }

            if ($serviceLocator->has($extension)) {
                $env->addExtension($serviceLocator->get($extension));
            } else {
                $env->addExtension(new $extension);
            }
        }

        $env->registerUndefinedFunctionCallback(function ($name) use ($viewHelperManager, $phpRenderer) {
            if (!$viewHelperManager->has($name)) {
                return false;
            }
            
            $callable = [$phpRenderer->plugin($name), '__invoke'];
            $options = [
                'is_safe' => ['all']
            ];
            return new Twig_SimpleFunction(null, $callable, $options);
        });

        return $env;
    }

}
