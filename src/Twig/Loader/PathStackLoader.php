<?php
namespace ZfExtra\Twig\Loader;

use Twig_Loader_Filesystem;

class PathStackLoader extends Twig_Loader_Filesystem
{
    protected function normalizeName($name)
    {
        if (substr($name, -5) !== '.twig') {
            $name = $name . '.twig';
        }
        return $name;
    }
    
    /**
     * 
     */
    public function locateTemplate($name)
    {
        return $this->findTemplate($name, true);
    }
}
