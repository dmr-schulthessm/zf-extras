<?php

namespace ZfExtra\Twig\View;

use Zend\ServiceManager\Config;
use Zend\ServiceManager\ConfigInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Exception\RuntimeException;
use ZfExtra\Config\ConfigHelper;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@muehlemann-popp.ch>
 */
class HelperPluginManagerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get(ConfigHelper::class);
        $viewManager = $serviceLocator->get('ViewHelperManager');
        $managerConfigs = $config->get('twig.helper_manager.configs');

        $twigManager = new HelperPluginManager(new Config($config->get('twig.view_helpers')));
        $twigManager->setServiceLocator($serviceLocator);
        $twigManager->addPeeringServiceManager($viewManager);

        foreach ($managerConfigs as $configClass) {
            if (is_string($configClass) && class_exists($configClass)) {
                $config = new $configClass;
                if (!$config instanceof ConfigInterface) {
                    throw new RuntimeException(
                    sprintf(
                        'Invalid service manager configuration class provided; received "%s",
                        expected class implementing %s', $configClass, 'Zend\ServiceManager\ConfigInterface'
                     )
                    );
                }
                $config->configureServiceManager($twigManager);
            }
        }
        return $twigManager;
    }

}
