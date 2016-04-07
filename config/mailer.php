<?php

use Zend\Mail\Transport\Sendmail;

return [
    'mailer' => [
        'transport' => [
            'name' => Sendmail::class,
            'options' => [],
        ],
        'messages' => NULL,
    ],
];
