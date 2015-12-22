<?php

namespace ZfExtra\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;
use Zend\View\HelperPluginManager;
use ZfExtra\Config\ConfigHelper;
use ZfExtra\Exception\MethodNotFoundException;

/**
 * Helper to access configs from view layer.
 * 
 * @property HelperPluginManager $serviceLocator
 * 
 * @method array getConfig() Returns application config. See application.yml
 * @method array getFrameworkConfig() Returns framework config. See framework.yml
 * @method mixed getVariable(string $name) Return variable value.
 * @method mixed hasVariable(string $name) Test if variable exists.
 * 
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class Config extends AbstractHelper implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    /**
     *
     * @var ConfigHelper
     */
    protected $helper;

    /**
     * 
     * @param string $path
     * @param mixed $default
     * @return mixed|Config
     */
    public function __invoke($path = null, $default = null)
    {
        if (null == $path) {
            return $this;
        }

        return $this->getConfigHelper()->get($path, $default);
    }

    /**
     * Proxy to config helper.
     * 
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws MethodNotFoundException
     */
    public function __call($name, $arguments)
    {
        if (!method_exists($this->getConfigHelper(), $name)) {
            throw new MethodNotFoundException(sprintf(
                    'The method "%s" has not been found neither in "%s" nor in "%s".', $name, self::class, ConfigHelper::class
            ));
        }

        return call_user_func_array([$this->getConfigHelper(), $name], $arguments);
    }

    /**
     * 
     * @return ConfigHelper
     */
    public function getConfigHelper()
    {
        return $this->serviceLocator->getServiceLocator()->get('config_helper');
    }

}
