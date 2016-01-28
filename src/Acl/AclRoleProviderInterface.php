<?php

namespace ZfExtra\Acl;

interface AclRoleProviderInterface
{

    public function getRole();

    public function setRole($role);
}
