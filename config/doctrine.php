<?php

use Doctrine\DBAL\Driver\Mysqli\Driver;
use ZfExtra\Mvc\Controller\Plugin\Orm;

return [
    'controller_plugins' => [
        'invokables' => [
            'orm' => Orm::class
        ],
    ],
    'doctrine' => [
        'configuration' => [
            'orm_default' => [
                'metadata_cache' => 'array',
                'query_cache' => 'array',
                'result_cache' => 'array',
                'proxy_dir' => 'var/cache/doctrine/orm/proxies',
            ],
        ],
        'connection' => [
            'orm_default' => [
                'driverClass' => Driver::class,
            ],
        ],
    ],
];
