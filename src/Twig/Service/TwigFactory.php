<?php

namespace ZfExtra\Twig\Service;

use Twig_Environment;
use Twig_SimpleFunction;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Renderer\PhpRenderer;

class TwigFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $viewHelperManager = $serviceLocator->get('ViewHelperManager');
        $renderer = new PhpRenderer();
        $renderer->setHelperPluginManager($viewHelperManager);

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

        $env->registerUndefinedFunctionCallback(function ($name) use ($viewHelperManager, $renderer) {
            if (!$viewHelperManager->has($name)) {
                return false;
            }
            
            $callable = [$renderer->plugin($name), '__invoke'];
            $options = [
                'is_safe' => ['all']
            ];
            return new Twig_SimpleFunction(null, $callable, $options);
        });

        return $env;
    }

}
