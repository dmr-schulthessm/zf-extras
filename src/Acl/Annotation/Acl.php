<?php

namespace ZfExtra\Acl\Annotation;

/**
 * @Annotation
 */
class Acl
{

    /**
     *
     * @var string
     */
    public $allow;

    /**
     *
     * @var string
     */
    public $deny;

    /**
     * 
     * @return string
     */
    public function getAllow()
    {
        return $this->allow;
    }

    /**
     * 
     * @return string
     */
    public function getDeny()
    {
        return $this->deny;
    }

}
