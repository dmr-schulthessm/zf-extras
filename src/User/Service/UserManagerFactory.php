<?php

/*
 * Zend Framework 2 extras.
 *
 * @link      https://github.com/alex-oleshkevich/zf-extras the canonical source repository.
 * @copyright Copyright (c) 2014-2016 Alex Oleshkevich <alex.oleshkevich@gmail.com>
 * @license   http://en.wikipedia.org/wiki/MIT_License MIT
 */

namespace ZfExtra\User\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\User\UserManager;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class UserManagerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config.helper');
        $em = $serviceLocator->get('doctrine.entitymanager.orm_default');
        return new UserManager($em, $config->get('user.entity_class'));
    }

}
