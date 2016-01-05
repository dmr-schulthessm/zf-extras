<?php

namespace ZfExtra\Mvc;

use Doctrine\Common\Persistence\ObjectManager;
use Exception;

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
     * @param string $class
     * @param string $method
     * @param array $params
     * @return array
     */
    public function makeArguments($class, $method, array $params)
    {
        if (isset($this->config['injections'][$class][$method])) {
            $injections = $this->config['injections'][$class][$method];
            
            reset($injections);
            foreach ($params as $variable => $value) {
                $injection = current($injections);
                if (is_array($injection)) {
                    list($entity, $manager) = $injection;
                    if (is_scalar($value)) {
                        $params[$variable] = call_user_func_array([$this->objectManagers[$manager]->getRepository($entity), 'find'], [$value]);
                    }
                }
                next($injections);
            }
        }
        return $params;
    }

}
