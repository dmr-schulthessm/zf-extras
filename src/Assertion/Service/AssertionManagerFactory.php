<?php
namespace ZfExtra\Assertion\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\Assertion\AssertionManager;

class AssertionManagerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new AssertionManager;
    }

}
