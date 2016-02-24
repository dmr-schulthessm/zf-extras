<?php

namespace ZfExtra\Twig;

use Twig_Extension;
use Twig_SimpleTest;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@muehlemann-popp.ch>
 */
class ExtraExtension extends Twig_Extension
{

    public function getName()
    {
        return 'zf_extra';
    }

    public function getTests()
    {
        return [
            new Twig_SimpleTest('instanceof', [$this, 'isInstanceOf'])
        ];
    }

    public function isInstanceOf($var, $instance)
    {
        return $var instanceof $instance;
    }

}
