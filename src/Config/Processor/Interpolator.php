<?php

namespace ZfExtra\Config\Processor;

use Exception;
use Zend\Config\Config;
use Zend\Config\Processor\ProcessorInterface;
use ZfExtra\Config\Interpolator as VariableInterpolator;

class Interpolator implements ProcessorInterface
{

    /**
     *
     * @var array
     */
    protected $variables;
    
    /**
     *
     * @var VariableInterpolator
     */
    protected $interpolator;

    /**
     * 
     * @param array $variables
     */
    public function __construct(array $variables = array())
    {
        $this->variables = $variables;
        $this->interpolator = new VariableInterpolator;
    }

    /**
     * 
     * @param Config $value
     * @return Config
     */
    public function process(Config $value)
    {
        return new Config($this->interpolator->interpolate($value->toArray(), $this->variables));
    }

    /**
     * 
     * @param string $value
     * @throws Exception
     */
    public function processValue($value)
    {
        throw new Exception('Not implemented.');
    }

}
