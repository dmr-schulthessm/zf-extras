<?php

namespace ZfExtra\Acl\RoleResolver;

use Zend\Mvc\MvcEvent;

/**
 *
 * @author alex
 */
interface RoleResolverInterface
{

    public function resolve(MvcEvent $event, $identity);
}
