<?php

namespace ZfExtra\Acl\RoleResolver;

use Zend\Mvc\MvcEvent;
use ZfExtra\Acl\AclRoleProviderInterface;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@muehlemann-popp.ch>
 */
class RoleResolver implements RoleResolverInterface
{

    /**
     *
     * @var string
     */
    protected $defaultRole;

    /**
     * 
     * @param string $defaultRole
     */
    public function __construct($defaultRole)
    {
        $this->defaultRole = $defaultRole;
    }

    /**
     * 
     * @param MvcEvent $event
     * @param AclRoleProviderInterface $identity
     * @return string
     */
    public function resolve(MvcEvent $event, $identity)
    {
        $sm = $event->getApplication()->getServiceManager();
        $role = $this->defaultRole;
        if (is_object($identity) && $identity instanceof AclRoleProviderInterface) {
            $role = $identity->getRole();
        }
        return $role;
    }

}
