<?php

namespace ZfExtra\Asset\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Image extends AbstractHelper
{

    public function __invoke($path, $alt = '', array $attrs = null, $module = 'app')
    {
        $attributes = [];
        if ($attrs) {
            foreach ($attrs as $name => $value) {
                $attributes[] = sprintf('%s="%s"', $name, $value);
            }
        }
        $path = sprintf('/assets/%s/%s', strtolower($module), $path);
        return sprintf('<img src="%s" alt="%s" %s />', $path, $alt, join(' ', $attributes));
    }

}
