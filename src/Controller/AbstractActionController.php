<?php

namespace ZfExtra\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Zend\EventManager\EventManager;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\Controller\AbstractActionController as ZendAbstractActionController;
use Zend\Mvc\Exception\DomainException;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;
use Zend\Session\Container;
use Zend\Session\ManagerInterface;
use Zend\View\Model\ViewModel;
use ZfExtra\Controller\Plugin\Config;
use ZfExtra\Entity\AbstractEntity;
use ZfExtra\Mail\Mailer;

/**
 * Base action controller.
 * 
 * @method Config config(string $path = null, mixed $default = null) Get config value by path.
 * @method ServiceManager|mixed service(string $name = null) Get a registered service or get a service manager.
 * @method string render(ViewModel $viewModel) Render a template using ViewModel class.
 * @method Container session(string $containerName = 'default', ManagerInterface $manager = null) Returns a session container.
 * @method Mailer mailer() Send email message or get a mailer instance.
 * @method EventManager events() Return app's event manager.
 * @method EntityRepository|EntityManager|AbstractEntity orm(string $repositoryClass = null, string $entityManager = 'doctrine.entitymanager.orm_default') Get doctrine entity manager.
 * @method string translate($message, $textDomain = 'default', $locale = null) Translate a string
 * 
 * @property Request $request
 * @property Response $response
 * 
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
abstract class AbstractActionController extends ZendAbstractActionController
{

    public function onDispatch(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();
        if (!$routeMatch) {
            /**
             * @todo Determine requirements for when route match is missing.
             *       Potentially allow pulling directly from request metadata?
             */
            throw new DomainException('Missing route matches; unsure how to retrieve action');
        }

        $action = $routeMatch->getParam('action', 'not-found');
        $method = static::getMethodFromAction($action);

        if (!method_exists($this, $method)) {
            $method = 'notFoundAction';
        }

        $actionResponse = call_user_func_array(array($this, $method), $e->getParam('__method_arguments'));
        $e->setResult($actionResponse);

        return $actionResponse;
    }

}
