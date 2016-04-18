<?php

use ZfExtra\View\DirectRenderer;
use ZfExtra\View\Factory\DirectRendererFactory;
use ZfExtra\View\LayoutSwitcherListener;

return [
    'view_layouts' => [
        'order' => [
            0 => 'action',
            1 => 'controller',
            2 => 'module',
            3 => 'route',
        ],
        'layouts' => [
            'route' => [],
            'module' => [],
            'controller' => [],
            'action' => [],
        ],
    ],
    'view_helpers' => [
        'aliases' => [],
    ],
    'service_manager' => [
        'invokables' => [
            LayoutSwitcherListener::class => LayoutSwitcherListener::class
        ],
        'factories' => [
            DirectRenderer::class => DirectRendererFactory::class,
        ],
    ],
];
