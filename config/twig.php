<?php

use Zend\Form\View\HelperConfig as FormHelperConfig;
use Zend\Navigation\View\HelperConfig as NavHelperConfig;
use ZfExtra\Twig\DirectRenderer;
use ZfExtra\Twig\Loader\PathStackLoader;
use ZfExtra\Twig\Loader\TemplateMapLoader;
use ZfExtra\Twig\Service\LoaderFactory;
use ZfExtra\Twig\Service\PathStackLoaderFactory;
use ZfExtra\Twig\Service\RendererFactory;
use ZfExtra\Twig\Service\TemplateMapLoaderFactory;
use ZfExtra\Twig\Service\TwigDirectRendererFactory;
use ZfExtra\Twig\Service\TwigFactory;
use ZfExtra\Twig\Service\TwigResolverFactory;
use ZfExtra\Twig\Service\TwigStrategyFactory;
use ZfExtra\Twig\View\HelperPluginManager;
use ZfExtra\Twig\View\HelperPluginManagerFactory;
use ZfExtra\Twig\View\TwigRenderer;
use ZfExtra\Twig\View\TwigResolver;
use ZfExtra\Twig\View\TwigStrategy;

return [
    'service_manager' => [
        'factories' => [
            Twig_Environment::class => TwigFactory::class,
            TwigRenderer::class => RendererFactory::class,
            TwigResolver::class => TwigResolverFactory::class,
            Twig_LoaderInterface::class => LoaderFactory::class,
            PathStackLoader::class => PathStackLoaderFactory::class,
            TwigStrategy::class => TwigStrategyFactory::class,
            DirectRenderer::class => TwigDirectRendererFactory::class,
            HelperPluginManager::class => HelperPluginManagerFactory::class,
            TemplateMapLoader::class => TemplateMapLoaderFactory::class
        ],
        'aliases' => [
            'twig' => TwigRenderer::class,
            'TwigViewStrategy' => TwigStrategy::class
        ]
    ],
    'twig' => [
        'options' => [
            'debug' => false,
            'charset' => 'UTF-8',
            'base_template_class' => Twig_Template::class,
            'cache' => false,
            'auto_reload' => NULL,
            'strict_variables' => false,
            'autoescape' => 'html',
            'optimizations' => -1,
        ],
        'loaders' => [
            PathStackLoader::class,
            TemplateMapLoader::class,
        ],
        'extensions' => [],
        'view_helpers' => [
        ],
        'helper_manager' => array(
            'configs' => array(
                NavHelperConfig::class,
                FormHelperConfig::class,
            )
        )
    ],
];
