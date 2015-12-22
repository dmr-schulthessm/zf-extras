<?php
namespace ZfExtra\Assertion;

use Zend\Mvc\MvcEvent;

interface AssertionInterface
{
    public function test(MvcEvent $event);
    
    public function onFail(MvcEvent $event);
}
