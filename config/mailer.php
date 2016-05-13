<?php

use Zend\Mail\Transport\Sendmail;
use ZfExtra\Mail\Mailer;
use ZfExtra\Mail\Factory\MailerFactory;
use ZfExtra\Mail\Factory\MessageFactoryFactory;
use ZfExtra\Mail\MessageFactory;

return [
    'mailer' => [
        'transport' => [
            'name' => Sendmail::class,
            'options' => [],
        ],
        'messages' => [],
        'plugins' => []
    ],
    'service_manager' => [
        'factories' => [
            Mailer::class => MailerFactory::class,
            MessageFactory::class => MessageFactoryFactory::class
        ],
        'aliases' => [
            'mailer' => Mailer::class,
            'Mailer' => Mailer::class,
            'MessageFactory' => MessageFactory::class
        ],
    ],
];
