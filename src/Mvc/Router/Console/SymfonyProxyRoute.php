<?php

namespace ZfExtra\Mvc\Router\Console;

use BadMethodCallException;
use Symfony\Component\Console\Application;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\Router\Console\RouteInterface;
use Zend\Mvc\Router\Console\RouteMatch;
use Zend\Stdlib\RequestInterface as Request;

class SymfonyProxyRoute implements RouteInterface
{

    /**
     *
     * @var Application
     */
    protected $application;

    /**
     *
     * @var array
     */
    protected $defaults = array();

    public function __construct(Application $application, $defaults = array())
    {
        $this->application = $application;
        $this->defaults = $defaults;
    }

    public function match(Request $request)
    {
        if (!$request instanceof ConsoleRequest) {
            return null;
        }

        $params = $request->getParams()->toArray();

        if (!isset($params[0]) || !$this->application->has($params[0])) {
            return null;
        }

        return new RouteMatch($this->defaults);
    }

    /**
     * Disabled.
     *
     * @throws BadMethodCallException this method is disabled
     */
    public function assemble(array $params = array(), array $options = array())
    {
        throw new BadMethodCallException('Unsupported');
    }

    public function getAssembledParams()
    {
        return array();
    }

    /**
     * Disabled.
     *
     * @throws BadMethodCallException this method is disabled
     */
    public static function factory($options = array())
    {
        throw new BadMethodCallException('Unsupported');
    }

}
