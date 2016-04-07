<?php

use ZfExtra\Log\EventListener\LogEventListener;
use ZfExtra\Log\EventListener\MvcEventListener;
use ZfExtra\Log\Factory\ApplicationLoggerFactory;
use ZfExtra\Log\Factory\ApplicationLoggerFormatterFactory;
use ZfExtra\Log\Factory\ApplicationLoggerPriorityFilterFactory;
use ZfExtra\Log\Factory\ApplicationLoggerStreamWriterFactory;
use ZfExtra\Log\Factory\LogEventListenerFactory;

return [
    'logger' =>
    [
        'enable' => true,
        'service' => 'ZfExtra\Log\ApplicationLogger',
        'formatter' => 'ZfExtra\Log\ApplicationLoggerFormatter',
        'priority' => 6,
        'writers' => [
            0 => 'ZfExtra\Log\ApplicationLoggerStreamWriter',
        ],
        'filters' => [
            0 => 'ZfExtra\Log\ApplicationLoggerPriorityFilter',
        ],
    ],
    'service_manager' => [
        'invokables' => [
            MvcEventListener::class => MvcEventListener::class,
        ],
        'factories' => [
            LogEventListener::class => LogEventListenerFactory::class,
            'ZfExtra\Log\ApplicationLogger' => ApplicationLoggerFactory::class,
            'ZfExtra\Log\ApplicationLoggerFormatter' => ApplicationLoggerFormatterFactory::class,
            'ZfExtra\Log\ApplicationLoggerStreamWriter' => ApplicationLoggerStreamWriterFactory::class,
            'ZfExtra\Log\ApplicationLoggerPriorityFilter' => ApplicationLoggerPriorityFilterFactory::class,
        ],
    ],
];
