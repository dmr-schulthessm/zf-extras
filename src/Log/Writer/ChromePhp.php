<?php

namespace ZfExtra\Log\Writer;

use ChromePhp as ChromePhpClass;
use Zend\Log\Formatter\ChromePhp as ChromePhpFormatter;
use Zend\Log\Logger;
use Zend\Log\Writer\AbstractWriter;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@muehlemann-popp.ch>
 */
class ChromePhp extends AbstractWriter
{

    /**
     *
     * @var ChromePhpClass
     */
    protected $chromephp;

    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->chromephp = ChromePhpClass::getInstance();
        $this->formatter = new ChromePhpFormatter;
    }

    /**
     * Write a message to the log.
     *
     * @param array $event event data
     * @return void
     */
    protected function doWrite(array $event)
    {
        $line = $this->formatter->format($event);
        $func = 'log';

        switch ($event['priority']) {
            case Logger::EMERG:
            case Logger::ALERT:
            case Logger::CRIT:
            case Logger::ERR:
                $func = 'error';
                break;
            case Logger::WARN:
                $func = 'warn';
                break;
            case Logger::NOTICE:
            case Logger::INFO:
                $func = 'info';
                break;
            case Logger::DEBUG:
                $func = 'log';
                break;
            default:
                $func = 'log';
                break;
        }
        
        $args = $event['extra'];
        array_unshift($args, $line);
        call_user_func_array([$this->chromephp, $func], $args);
    }

}
