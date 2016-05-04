<?php

namespace ZfExtra\Console;

use Symfony\Component\Console\Application;
use Zend\Console\Request;
use Zend\Mvc\Controller\AbstractConsoleController;
use Zend\View\Model\ConsoleModel;
use ZfExtra\Console\Input\RequestInput;

class ConsoleController extends AbstractConsoleController
{

    /**
     *
     * @var Application
     */
    protected $application;

    /**
     * 
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * 
     * @return ConsoleModel
     */
    public function runAction()
    {
        $exitCode = $this->application->run(new RequestInput($this->getRequest()));
        if (is_numeric($exitCode)) {
            $model = new ConsoleModel();
            $model->setErrorLevel($exitCode);

            return $model;
        }
    }

}
