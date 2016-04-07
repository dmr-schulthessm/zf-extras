<?php

use ZfExtra\Config\ConfigHelper;
use ZfExtra\Config\ConfigHelperFactory;
use ZfExtra\Config\Controller\Plugin\Config as ConfigPlugin;
use ZfExtra\Config\View\Helper\Config as ConfigViewHelper;

return [
    'controller_plugins' => [
        'invokables' => [
            'config' => ConfigPlugin::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'config' => ConfigViewHelper::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            ConfigHelper::class => ConfigHelperFactory::class,
        ],
        'aliases' => [
            'config.helper' => ConfigHelper::class,
            'config_helper' => ConfigHelper::class,
        ],
    ],
];
