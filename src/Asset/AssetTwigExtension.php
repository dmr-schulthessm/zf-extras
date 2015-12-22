<?php

namespace ZfExtra\Asset;

use Twig_Extension;
use Twig_SimpleFunction;

class AssetTwigExtension extends Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('image', [$this, 'image'], ['is_safe' => ['html']])
        );
    }
    
    public function getName()
    {
        return 'assets';
    }
    
    public function image($path, $alt = '', $attrs = [], $module = 'app')
    {
        $attributes = [];
        foreach ($attrs as $name => $value) {
            $attributes[] = sprintf('%s="%s"', $name, $value);
        }
        $path = sprintf('/assets/%s/%s', $module, $path);
        return sprintf('<img src="%s" alt="%s" %s />', $path, $alt, join(' ', $attributes));
    }

}
