<?php

namespace ZfExtra\View;

use Zend\View\Model\ModelInterface;
use Zend\View\Renderer\RendererInterface;
use Zend\View\View;
use Zend\View\ViewEvent;

/**
 * Renders ModelInterface using configured view renderers.
 * 
 * @author Alex Oleshkevich <alex.oleshkevich@muehlemann-popp.ch>
 */
class DirectRenderer implements DirectRendererInterface
{

    /**
     *
     * @var View
     */
    protected $view;

    /**
     * 
     * @param View $view
     */
    public function __construct(View $view)
    {
        $this->view = $view;
    }

    /**
     * 
     * @param ModelInterface $model
     * @return string
     */
    public function render(ModelInterface $model)
    {
        $event = new ViewEvent;
        $event->setModel($model);
        $renderers = $this->view->getEventManager()->trigger(ViewEvent::EVENT_RENDERER, $event, function ($result) {
            return $result instanceof RendererInterface;
        });
        return $renderers->last()->render($model);
    }

}
