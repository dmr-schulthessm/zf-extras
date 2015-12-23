<?php

namespace ZfExtra\Asset\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Image extends AbstractHelper
{

    public function __invoke($path, $alt = '', $attrs = [], $module = 'app')
    {
        $attributes = [];
        foreach ($attrs as $name => $value) {
            $attributes[] = sprintf('%s="%s"', $name, $value);
        }
        $path = sprintf('/assets/%s/%s', $module, $path);
        return sprintf('<img src="%s" alt="%s" %s />', $path, $alt, join(' ', $attributes));
    }

}
