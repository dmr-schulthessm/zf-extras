<?php

namespace ZfExtra\Assertion\Annotation;

use ZfAnnotation\Annotation\Service;

/**
 * @Annotation
 */
class Assertion extends Service
{
    /**
     *
     * @var string
     */
    public $serviceManager = 'assertions';
    
}
