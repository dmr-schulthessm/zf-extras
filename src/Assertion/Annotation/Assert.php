<?php

namespace ZfExtra\Assertion\Annotation;

use Zend\Code\Annotation\AnnotationInterface;

/**
 * @Annotation
 */
class Assert implements AnnotationInterface
{

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var array
     */
    public $options = array();

    /**
     * 
     * @param string $content
     */
    public function initialize($content)
    {
        
    }

    /**
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function getOptions()
    {
        return $this->options;
    }

}
