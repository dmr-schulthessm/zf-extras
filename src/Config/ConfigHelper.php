<?php

namespace ZfExtra\Config;

use Exception;
use ZfExtra\Exception\MissingConfigParamException;
use ZfExtra\Exception\MissingVariableException;

/**
 * Config helper with handy utils for accessing config variables.
 * 
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class ConfigHelper
{
    use ValueFinderTrait;
    
    /**
     * Application config.
     * 
     * @var array
     */
    protected $config;

    /**
     * A key associated with variables section in config.
     * 
     * @var string
     */
    protected $variablesKey;

    /**
     * Constructor.
     * @param array $config
     */
    public function __construct(array $config, array $frameworkConfig = array(), $variablesKey = 'variables')
    {
        $this->config = array_replace_recursive($config, $frameworkConfig);
        $this->variablesKey = $variablesKey;
    }

    /**
     * Try to find config option by $path othwerwise return $default.
     * 
     * @param string $path
     * @param mixed $default
     * @param bool $strict Throw and exception if key is not set.
     * @return mxied
     */
    public function get($path, $default = null, $strict = false)
    {
        return $this->find($this->config, $path, $default, $strict);
    }

    /**
     * Returns merged configs of modules and application.
     * 
     * @see config/application.yml
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * 
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function getVariable($name)
    {
        $varName = sprintf('%s.%s', $this->variablesKey, $name);
        try {
            return $this->get($varName, null, true);
        } catch (MissingConfigParamException $e) {
            $ex = new MissingVariableException('Variable "' . $varName . '" not found in variables.', 0, $e);
            throw $ex;
        }
    }

    /**
     * 
     * @param string $name
     * @return bool
     */
    public function hasVariable($name)
    {
        return isset($this->config[$this->variablesKey][$name]);
    }

    /**
     * 
     * @return array
     */
    public function getVariables()
    {
        return $this->config[$this->variablesKey];
    }

}
