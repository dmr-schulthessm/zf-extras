<?php

namespace ZfExtra\Assertion;

use Zend\Mvc\MvcEvent;

interface AssertionInterface
{

    public function test(MvcEvent $event, array $options = null);

    public function onFail(MvcEvent $event);
}
