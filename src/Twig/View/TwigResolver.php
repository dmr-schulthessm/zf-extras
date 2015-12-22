<?php
namespace ZfExtra\Twig\View;

use Twig_Environment;
use Twig_TemplateInterface;
use Zend\View\Renderer\RendererInterface;
use Zend\View\Resolver\ResolverInterface;

class TwigResolver implements ResolverInterface
{
    /**
     *
     * @var Twig_Environment
     */
    protected $environment;
    
    /**
     * 
     * @param Twig_Environment $environment
     */
    public function __construct(Twig_Environment $environment)
    {
        $this->environment = $environment;
    }
    
    /**
     * Loads template by name.
     * 
     * @param string $name
     * @param RendererInterface $renderer
     * @return Twig_TemplateInterface
     */
    public function resolve($name, RendererInterface $renderer = null)
    {
        return $this->environment->loadTemplate($name);
    }

}
