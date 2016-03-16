<?php

namespace ZfExtra\Acl\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\Acl\RoleResolver\RoleResolver;
use ZfExtra\Config\ConfigHelper;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@muehlemann-popp.ch>
 */
class RoleResolverFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get(ConfigHelper::class)->get('acl');
        return new RoleResolver($config['default_role']);
    }

}
