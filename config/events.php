<?php

use ZfExtra\EventListener\SharedEventsListener;

return [
    'listeners' => [
        SharedEventsListener::class
    ],
    'module_event_listeners' => [],
    'shared_event_listeners' => [],
    'service_manager' => [
        'invokables' => [
            SharedEventsListener::class => SharedEventsListener::class,
        ],
    ],
];
