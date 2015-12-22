<?php
namespace ZfExtra\Config;

use Zend\Config\Config as ZendConfig;
use Zend\Config\Processor\ProcessorInterface;

class ProcessorChain
{
    /**
     *
     * @var ProcessorInterface
     */
    protected $processors = array();
    
    /**
     * 
     * @param ProcessorInterface $processor
     */
    public function add(ProcessorInterface $processor)
    {
        $this->processors[] = $processor;
    }
    
    /**
     * 
     * @param ZendConfig $config
     * @return ZendConfig
     */
    public function process(ZendConfig $config)
    {
        foreach ($this->processors as $processor) {
            $config = $processor->process($config);
        }
        return $config;
    }
}