<?php

use ZfExtra\Asset\AssetManager;
use ZfExtra\Asset\Command\InstallAssetsCommand;
use ZfExtra\Asset\Service\AssetManagerFactory;
use ZfExtra\Asset\View\Helper\Image;

return [
    'assets' => [
        'module_assets_dir' => 'assets',
        'install_dir' => 'public/assets',
    ],
    'command_manager' => [
        'invokables' => [
            InstallAssetsCommand::class => InstallAssetsCommand::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            AssetManager::class => AssetManagerFactory::class
        ],
        'aliases' => [
            'assets' => AssetManager::class
        ]
    ],
];
