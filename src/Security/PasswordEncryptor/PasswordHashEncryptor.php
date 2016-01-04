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
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class PasswordHashEncryptor implements PasswordEncryptorInterface
{

    /**
     *
     * @var int
     */
    protected $algo = PASSWORD_BCRYPT;

    /**
     *
     * @var array
     */
    protected $options = array();

    /**
     * 
     * @param int $algo
     * @param array $options
     */
    public function __construct($algo = null, array $options = array())
    {
        if (null !== $algo) {
            $this->algo = $algo;
        }

        if (null !== $options) {
            $this->options = $options;
        }
    }

    /**
     * 
     * @param string $password
     * @return string
     */
    public function encrypt($password)
    {
        return password_hash($password, $this->algo, $this->options);
    }
    
    /**
     * 
     * @param string $plainPassword
     * @param string $hash
     * @return boolean
     */
    public function verify($plainPassword, $hash)
    {
        if (password_verify($plainPassword, $hash)) {
            if (password_needs_rehash($hash, $this->algo, $this->options)) {
                $plainPassword = password_hash($plainPassword, $this->algo, $this->options);
            }
            return true;
        }
        return false;
    }

}
