<?php

/**
 * Zend Framework 2 extras
 *
 * @link      https://github.com/alex-oleshkevich/zf-extras the canonical source repository.
 * @copyright Copyright (c) 2014-2016 Alex Oleshkevich <alex.oleshkevich@gmail.com>
 * @license   http://en.wikipedia.org/wiki/MIT_License MIT
 */

namespace ZfExtra\User;

use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Authentication\Result;
use Zend\Http\Request;
use ZfExtra\Config\ValueFinderTrait;

/**
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class Auth
{
    use ValueFinderTrait;
    
    /**
     *
     * @var AuthenticationServiceInterface
     */
    protected $authService;
    
    /**
     *
     * @var array
     */
    protected $options = array();
    
    public function __construct(AuthenticationServiceInterface $authService, array $options = array())
    {
        $this->authService = $authService;
        $this->options = $options;
    }
    
    /**
     * 
     * @param string $identity
     * @param string $credential
     * @return Result
     */
    public function login($identity, $credential)
    {
        $adapter = $this->authService->getAdapter();
        $adapter->setIdentity($identity);
        $adapter->setCredential($credential);
        return $this->authService->authenticate($adapter);
    }
    
    /**
     * 
     * @param Request $request
     * @return Result
     */
    public function loginViaRequest(Request $request)
    {
        $identity = $this->find($this->options, 'request_key_identity');
        $credential = $this->find($this->options, 'request_key_credential');
        return $this->login($request->getPost($identity), $request->getPost($credential));
    }
    
    /**
     * Logout and destroy user's session.
     */
    public function logout()
    {
        $this->authService->clearIdentity();
        session_regenerate_id();
        session_destroy();
    }
    
    /**
     * 
     * @return UserInterface
     */
    public function getIdentity()
    {
        return $this->authService->getIdentity();
    }
}
