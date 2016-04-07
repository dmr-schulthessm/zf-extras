<?php

use Zend\Permissions\Acl\Acl;
use ZfExtra\Acl\AclListener;
use ZfExtra\Acl\Annotation\Acl as AclAnnotation;
use ZfExtra\Acl\Annotation\AnnotationListener;
use ZfExtra\Acl\Factory\AclListenerFactory;
use ZfExtra\Acl\Factory\RoleResolverFactory;
use ZfExtra\Acl\RoleResolver\RoleResolver;
use ZfExtra\Acl\Service\AclServiceFactory;

return [
    'acl' => [
        'roles' => [],
        'resources' => [
            'allow' => [],
            'deny' => [],
        ],
        'default_role' => 'guest',
        'policy' => 'restrictive',
        'violation_handlers' => [
            'unauthenticated' => null,
            'unauthorized' => null,
        ],
        'role_resolver' => RoleResolver::class,
    ],
    'service_manager' => [
        'factories' => [
            Acl::class => AclServiceFactory::class,
            AclListener::class => AclListenerFactory::class,
            RoleResolver::class => RoleResolverFactory::class
        ],
        'aliases' => [
            'acl' => Acl::class
        ]
    ],
    'zf_annotation' => [
        'annotations' => [
            AclAnnotation::class,
        ],
        'event_listeners' => [
            AnnotationListener::class
        ],
    ],
];
