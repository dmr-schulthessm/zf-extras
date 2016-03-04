<?php

namespace ZfExtra\Command\Annotation;

use ZfAnnotation\Annotation\Service;

/**
 * @Annotation
 */
class Command extends Service
{

    /**
     * @var string
     */
    public $serviceManager = 'command_manager';

}
