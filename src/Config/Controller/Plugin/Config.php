<?php

namespace ZfExtra\Config\Controller\Plugin;

use Exception;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use ZfExtra\Config\ConfigHelper;
use ZfExtra\Exception\MethodNotFoundException;

/**
 * @method array getConfig() Returns application config. See application.yml
 * @method array getFrameworkConfig() Returns framework config. See framework.yml
 * @method mixed getVariables() Return all variables.
 * @method mixed getVariable(string $name) Return variable value.
 * @method mixed hasVariable(string $name) Test if variable exists.
 * 
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class Config extends AbstractPlugin
{

    /**
     *
     * @var ConfigHelper
     */
    protected $configHelper;

    /**
     * 
     * @param ConfigHelper $configHelper
     */
    public function __construct(ConfigHelper $configHelper)
    {
        $this->configHelper = $configHelper;
    }

    /**
     * Returns value from config by $path.
     * 
     * @param string $path
     * @return Config|mixed
     */
    public function __invoke($path = null)
    {
        if (null == $path) {
            return $this;
        } else {
            if ($path[0] == '$') {
                return $this->getConfigHelper()->getVariable(substr($path, 1));
            } else {
                return $this->getConfigHelper()->get($path);
            }
        }
    }

    /**
     * Return ConfigHelper service.
     * 
     * @return ConfigHelper
     */
    public function getConfigHelper()
    {
        return $this->configHelper;
    }

    /**
     * Proxy to ConfigHelper instance.
     * 
     * @param string $name
     * @param array $arguments
     * @throws Exception
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

}
