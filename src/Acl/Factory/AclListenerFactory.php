<?php

namespace ZfExtra\Acl\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\Acl\AclListener;
use ZfExtra\Config\ConfigHelper;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@muehlemann-popp.ch>
 */
class AclListenerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get(ConfigHelper::class)->get('acl');
        $roleResolver = $serviceLocator->get($config['role_resolver']);
        return new AclListener($roleResolver);
    }

}
