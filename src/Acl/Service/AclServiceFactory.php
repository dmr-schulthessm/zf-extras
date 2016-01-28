<?php

namespace ZfExtra\Acl\Service;

use Exception;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\GenericResource;
use Zend\Permissions\Acl\Role\GenericRole;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\Navigation;

class AclServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config.helper')->get('acl');

        $acl = new Acl();
        foreach ($config['roles'] as $role => $parents) {
            if (empty($parents)) {
                $parents = null;
            }
            $role = new GenericRole($role);
            $acl->addRole($role, $parents);
        }

        foreach ($config['resources'] as $permission => $controllers) {
            foreach ($controllers as $controller => $actions) {
                if (!$acl->hasResource($controller)) {
                    $acl->addResource(new GenericResource($controller));
                }

                foreach ($actions as $action => $role) {
                    if ($action == '*') {
                        $action = null;
                    }

                    if ($permission == 'allow') {
                        $acl->allow($role, $controller, $action);
                    } elseif ($permission == 'deny') {
                        $acl->deny($role, $controller, $action);
                    } else {
                        throw new Exception('No valid permission defined: ' . $permission);
                    }
                }
            }
        }

        if (class_exists('Zend\View\Helper\Navigation')) {
            Navigation::setDefaultAcl($acl);
        }
        return $acl;
    }

}
