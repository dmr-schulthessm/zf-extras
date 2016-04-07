<?php

use ZfExtra\Console\Command\Annotation\Command;
use ZfExtra\Console\Command\DebugConfigCommand;
use ZfExtra\Console\Command\DebugRouterCommand;
use ZfExtra\Console\Command\DebugServiceCommand;
use ZfExtra\Console\Command\ListCommand;
use ZfExtra\Console\CommandManager;
use ZfExtra\Console\ConsoleController;
use ZfExtra\Console\Factory\CommandManagerFactory;
use ZfExtra\Console\Factory\ConsoleControllerFactory;
use ZfExtra\Console\Factory\ConsoleFactory;
use ZfExtra\Mvc\Factory\SymfonyProxyRouteFactory;

return [
    'service_manager' => [
        'factories' => [
            'cli' => ConsoleFactory::class,
            CommandManager::class => CommandManagerFactory::class
        ],
    ],
    'command_manager' => [
        'invokables' => [
            ListCommand::class => ListCommand::class,
            DebugServiceCommand::class => DebugServiceCommand::class,
            DebugRouterCommand::class => DebugRouterCommand::class,
            DebugConfigCommand::class => DebugConfigCommand::class,
        ],
    ],
    'route_manager' => [
        'factories' => [
            'symfony_proxy' => SymfonyProxyRouteFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            ConsoleController::class => ConsoleControllerFactory::class,
        ],
    ],
    'zf_annotation' => [
        'annotations' => [
            Command::class
        ],
    ],
    'console' => [
        'router' => [
            'defaults' => [
                'controller' => ConsoleController::class,
                'action' => 'run',
            ],
            'routes' => [
                'symfony-proxy' => [
                    'type' => 'catchall',
                    'options' => [
                        'defaults' => [
                            'controller' => ConsoleController::class,
                            'action' => 'run',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
