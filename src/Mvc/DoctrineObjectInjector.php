<?php

namespace ZfExtra\Mvc;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityNotFoundException;
use Zend\Filter\Word\DashToCamelCase;

class DoctrineObjectInjector
{

    /**
     *
     * @var array
     */
    protected $config = [];
    
    /**
     *
     * @var ObjectManager
     */
    protected $objectManagers = [];

    /**
     * 
     * @param array $config
     * @param array $objectManagers
     */
    public function __construct(array $config, array $objectManagers)
    {
        $this->config = $config;
        $this->objectManagers = $objectManagers;
    }

    /**
     * 
     * @param string $controller
     * @param string $action
     * @param array $params
     * @return array
     */
    public function makeArguments($controller, $action, array $params)
    {
        $filter = new DashToCamelCase;
        $action = lcfirst($filter->filter($action));
        $result = array();
        if (isset($this->config['injections'][$controller][$action])) {
            $injections = $this->config['injections'][$controller][$action];
            
            reset($injections);
            foreach ($params as $variable => $value) {
                $name = key($injections);
                $injection = current($injections);
                if (is_array($injection)) {
                    list($entity, $manager) = $injection;
                    if (is_scalar($value)) {
                        $entity = call_user_func_array([$this->objectManagers[$manager]->getRepository($entity), 'find'], [$value]);
                        if (!$entity) {
                            throw new EntityNotFoundException;
                        }
                        $result[$name] = $entity;
                    }
                } else {
                    $result[$variable] = $value;
                }
                next($injections);
            }
        }
        return $result;
    }

}
