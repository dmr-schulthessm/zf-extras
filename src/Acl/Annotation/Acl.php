<?php

namespace ZfExtra\Acl\Annotation;

use Zend\Code\Annotation\AnnotationInterface;

/**
 * @Annotation
 */
class Acl implements AnnotationInterface
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
     * @param string $content
     */
    public function initialize($content)
    {
        
    }

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
