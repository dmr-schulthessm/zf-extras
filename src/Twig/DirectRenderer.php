<?php

namespace ZfExtra\Twig;

use Zend\View\Model\ModelInterface;
use ZfExtra\Twig\View\TwigRenderer;
use ZfExtra\View\DirectRendererInterface;

/**
 * Renders ModelInterface using configured view renderers.
 * 
 * @author Alex Oleshkevich <alex.oleshkevich@muehlemann-popp.ch>
 */
class DirectRenderer implements DirectRendererInterface
{

    /**
     *
     * @var TwigRenderer
     */
    protected $renderer;

    /**
     * 
     * @param TwigRenderer $renderer
     */
    public function __construct(TwigRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * 
     * @param ModelInterface $model
     * @return string
     */
    public function render(ModelInterface $model)
    {
        return $this->renderer->render($model);
    }

}
