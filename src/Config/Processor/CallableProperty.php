<?php
namespace ZfExtra\Config\Processor;

use Zend\Config\Config;
use Zend\Config\Processor\ProcessorInterface;
use ZendXml\Exception\InvalidArgumentException;

class CallableProperty implements ProcessorInterface
{

    /**
     * Process
     *
     * @param  Config $config
     * @return Config
     * @throws InvalidArgumentException
     */
    public function process(Config $config)
    {
        $data = $config->toArray();
        array_walk_recursive($data, function (&$value, $key) {
            if (preg_match('/callable\((.*)\)/', $value, $matches)) {
                list ($class, $method) = array_map('trim', explode(',', $matches[1]));
                $value = [new $class, $method];
            }
        });
        
        return new Config($data, true);
    }
    
    public function processValue($value)
    {
        
    }

}
