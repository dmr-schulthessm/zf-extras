<?php
namespace ZfExtra\Assertion\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\Assertion\Asserter;

class AsserterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Asserter;
    }

}
