<?php

use ZfExtra\Assertion\Annotation\Assert;
use ZfExtra\Assertion\Annotation\Assertion;
use ZfExtra\Assertion\Annotation\Asserts;
use ZfExtra\Assertion\Factory\AssertionListenerFactory;
use ZfExtra\Assertion\Listener\AnnotationListener;
use ZfExtra\Assertion\Listener\AssertionListener;

return [
    'zf_annotation' => [
        'annotations' => [
            Assertion::class,
            Assert::class,
            Asserts::class,
        ],
        'event_listeners' => [
            AnnotationListener::class
        ],
    ],
    'service_manager' => [
        'factories' => [
            AssertionListener::class => AssertionListenerFactory::class,
        ],
    ],
    'listeners' => [
        AssertionListener::class
    ],
];
