<?php

use ZfExtra\User\Auth;
use ZfExtra\User\Command\CreateUserCommand;
use ZfExtra\User\Service\AuthFactory;
use ZfExtra\User\Service\UserManagerFactory;
use ZfExtra\User\UserManager;

return [
    'auth' => [
        'service' => null,
        'options' => [
            'request_key_identity' => 'email',
            'request_key_credential' => 'password',
        ],
        'redirect' => [
            'after_login' => null,
            'after_logout' => null,
        ],
    ],
    'user' => [
        'entity_class' => null,
    ],
    'service_manager' => [
        'factories' => [
            Auth::class => AuthFactory::class,
            UserManager::class => UserManagerFactory::class,
        ],
        'aliases' => [
            'auth' => Auth::class,
            'user_manager' => UserManager::class
        ]
    ],
    'command_manager' => [
        'invokables' => [
            CreateUserCommand::class => CreateUserCommand::class,
        ],
    ],
];
