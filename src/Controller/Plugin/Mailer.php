<?php

namespace ZfExtra\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\Controller\PluginManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use ZfExtra\Exception\MethodNotFoundException;
use ZfExtra\Mail\Mailer as MailerObject;

/**
 * Allows to send email from controller.
 * 
 * @property PluginManager $serviceLocator Controller plugin manager.
 * 
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class Mailer extends AbstractPlugin implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    /**
     * 
     * @return Mailer
     */
    public function __invoke()
    {
        return $this;
    }
    
    /**
     * Proxy to Mailer service.
     * 
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws MethodNotFoundException
     */
    public function __call($name, $arguments)
    {
        if (!method_exists($this->getMailer(), $name)) {
            throw new MethodNotFoundException(sprintf(
                    'The method "%s" has not been found neither in "%s" nor in "%s".', $name, self::class, Mailer::class
            ));
        }

        return call_user_func_array([$this->getMailer(), $name], $arguments);
    }

    /**
     * Returns a registered view renderer.
     * 
     * @return MailerObject
     */
    public function getMailer()
    {
        return $this->serviceLocator->getServiceLocator()->get('mailer');
    }

}
