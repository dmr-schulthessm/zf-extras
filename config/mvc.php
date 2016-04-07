<?php

use Doctrine\ORM\EntityManager;
use ZfExtra\Entity\EntityInterface;
use ZfExtra\Mvc\Controller\Factory\RenderPluginFactory;
use ZfExtra\Mvc\Controller\Plugin\Form;
use ZfExtra\Mvc\Controller\Plugin\Mailer;
use ZfExtra\Mvc\Controller\Plugin\Service;
use ZfExtra\Mvc\Controller\Plugin\Session;
use ZfExtra\Mvc\Controller\Plugin\Translate;
use ZfExtra\Mvc\Factory\DoctrineObjectInjectorFactory;
use ZfExtra\Mvc\Listener\DoctrineObjectInjectionListener;

return [
    'mvc' => [
        'doctrine_object_injector' => [
            'object_managers' => [
                'orm' => EntityManager::class,
            ],
            'object_mapping' => [
                'orm' => [
                    EntityInterface::class
                ],
            ],
            'injections' => [],
        ],
    ],
    'controller_plugins' => [
        'invokables' => [
            'service' => Service::class,
            'session' => Session::class,
            'mailer' => Mailer::class,
            'translate' => Translate::class,
            'form' => Form::class,
        ],
        'factories' => [
            'render' => RenderPluginFactory::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            DoctrineObjectInjectionListener::class => DoctrineObjectInjectionListener::class,
        ],
        'factories' => [
            'doctrine_object_injector' => DoctrineObjectInjectorFactory::class,
        ],
        'aliases' => [
            DoctrineObjectInjectionListener::class => 'doctrine_object_injector_listener',
        ],
    ],
];
