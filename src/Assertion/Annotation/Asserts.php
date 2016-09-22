<?php

namespace ZfExtra\Assertion\Annotation;

/**
 * @Annotation
 */
class Asserts
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
