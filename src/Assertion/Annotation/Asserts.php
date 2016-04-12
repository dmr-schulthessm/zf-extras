<?php

namespace ZfExtra\Assertion\Annotation;

use Zend\Code\Annotation\AnnotationInterface;

/**
 * @Annotation
 */
class Asserts implements AnnotationInterface
{

    /**
     *
     * @var string
     */
    public $names;

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
    public function getNames()
    {
        return array_map('trim', explode(',', $this->names));
    }

    /**
     * 
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

}
