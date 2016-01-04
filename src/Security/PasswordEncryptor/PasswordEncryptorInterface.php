<?php

/*
 * Zend Framework 2 extras.
 *
 * @link      https://github.com/alex-oleshkevich/zf-extras the canonical source repository.
 * @copyright Copyright (c) 2014-2016 Alex Oleshkevich <alex.oleshkevich@gmail.com>
 * @license   http://en.wikipedia.org/wiki/MIT_License MIT
 */

namespace ZfExtra\Security\PasswordEncryptor;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
interface PasswordEncryptorInterface
{

    public function encrypt($password);
    public function verify($password, $hash);
}
