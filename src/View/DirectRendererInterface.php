<?php

namespace ZfExtra\View;

use Zend\View\Model\ModelInterface;

/**
 *
 * @author alex
 */
interface DirectRendererInterface
{
    public function render(ModelInterface $model);
}
