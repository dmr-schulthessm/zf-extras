<?php

namespace ZfExtraTest\View;

use PHPUnit_Framework_TestCase;
use ZfExtra\View\LayoutSwitcherListener;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class LayoutSwitcherListenerTest extends PHPUnit_Framework_TestCase
{

    public function testDetectLayout()
    {
        $config = array(
            'order' => array('action', 'controller', 'module', 'route'),
            'layouts' => array(
                'action' => array(
                    'testController::testAction' => 'action.phtml',
                ),
                'controller' => array(
                    'testController' => 'controller.phtml'
                ),
                'module' => array(
                    'Module' => 'module.phtml'
                ),
                'route' => array(
                    '^matched' => 'route.phtml'
                )
            )
        );
        $listener = new LayoutSwitcherListener;
        
        $this->assertEquals('action.phtml', $listener->detectLayout($config, 'matched/route', 'Module', 'testController', 'testAction'));
        $this->assertEquals('controller.phtml', $listener->detectLayout($config, 'matched/route', 'Module', 'testController'));
        $this->assertEquals('module.phtml', $listener->detectLayout($config, 'matched/route', 'Module'));
        $this->assertEquals('route.phtml', $listener->detectLayout($config, 'matched/route'));
        $this->assertEquals(null, $listener->detectLayout($config));
    }

}
