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
use ZfExtra\User\Auth;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class AuthFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config.helper');
        $authService = $serviceLocator->get($config->get('auth.service'));
        return new Auth($authService, $config->get('auth.options'));
    }

}
