<?php

namespace ZfExtra\Mvc\Assertion;

use Zend\Mvc\MvcEvent;
use ZfExtra\Assertion\Annotation\Assertion;
use ZfExtra\Assertion\AssertionInterface;

/**
 * @Assertion(name="required")
 */
class Required implements AssertionInterface
{

    public function onFail(MvcEvent $event)
    {
        $event->getRouteMatch()->setParam('action', 'not-found');
    }

    public function test(MvcEvent $event, array $options = null)
    {
        $params = $event->getParam('__method_arguments');
        foreach ($options as $param) {
            if (!isset($params[$param]) || $params[$param] == null) {
                return false;
            }
        }
        return true;
    }

}
