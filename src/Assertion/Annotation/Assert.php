<?php

namespace ZfExtra\Assertion\Annotation;

/**
 * @Annotation
 */
class Assert
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
