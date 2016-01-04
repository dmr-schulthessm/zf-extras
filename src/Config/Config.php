<?php

namespace ZfExtra\Config;

use Zend\Config\Config as ZendConfig;
use Zend\Config\Factory;
use Zend\Config\Processor\Constant;
use ZfExtra\Config\Processor\CallableProperty;
use ZfExtra\Config\Processor\Env;
use ZfExtra\Config\Processor\Interpolator;

/**
 * Config reader.
 * 
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class Config
{
    const VARIABLES_KEY = 'variables';
    
    /**
     * 
     * @param string $filename
     * @return array
     */
    public static function load($filename)
    {
        $config = new ZendConfig(Factory::fromFile($filename), true);
        $variables = isset($config[self::VARIABLES_KEY]) ? $config[self::VARIABLES_KEY]->toArray() : array();
        
        $processorChain = new ProcessorChain;
        $processorChain->add(new Constant(false, 'const(', ')'));
        $processorChain->add(new Env('env(', ')'));
        $processorChain->add(new CallableProperty($variables));
        $processorChain->add(new Interpolator($variables));
                
        return $processorChain->process($config)->toArray();
    }

}
