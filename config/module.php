<?php

use Zend\Mvc\Application;
use ZfExtra\Mail\Mailer;
use ZfExtra\Mail\MailerFactory;
use ZfExtra\Mvc\ApplicationFactory;

$config = [
    'service_manager' => [
        'factories' => [
            Application::class => ApplicationFactory::class,
        ],
        'aliases' => [
            'Application' => Application::class
        ],
    ],
];

return array_merge_recursive(
    $config,
    require 'doctrine.php',
    require 'mailer.php',
    require 'twig.php',
    require 'console.php',
    require 'assets.php',
    require 'assertion.php',
    require 'view.php',
    require 'config.php',
    require 'user.php',
    require 'intl.php',
    require 'mvc.php',
    require 'acl.php',
    require 'events.php',
    require 'log.php'
);