<?php

use ZfExtra\Intl\Factory\IntlTwigExtensionFactory;
use ZfExtra\Intl\IntlTwigExtension;
use ZfExtra\Intl\Translator\Loader\YmlLoader;

return [
    'service_manager' => [
        'factories' => [
            IntlTwigExtension::class => IntlTwigExtensionFactory::class,
        ],
    ],
    'twig' => [
        'extensions' => [
            IntlTwigExtension::class,
        ],
    ],
    'translator' => [
        'locale' => 'en',
    ],
    'translator_plugins' => [
        'invokables' => [
            'yml' => YmlLoader::class,
        ],
    ],
];
