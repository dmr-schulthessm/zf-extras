<?php
namespace ZfExtra\Config\Processor;

use Zend\Config\Processor\Token;

class Env extends Token
{
    /**
     * @param string $prefix                Optional prefix
     * @param string $suffix                Optional suffix
     */
    public function __construct($prefix = '', $suffix = '')
    {
        // if $_ENV is empty, check php.ini option 'variables_order'
        // it must contain 'E'
        parent::__construct($_ENV, $prefix, $suffix);
    }
}
