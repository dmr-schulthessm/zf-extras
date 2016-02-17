<?php

namespace ZfExtra\Twig\View;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\View\Model\ViewModel;
use Zend\View\ViewEvent;

class TwigStrategy extends AbstractListenerAggregate
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
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(ViewEvent::EVENT_RENDERER, [$this, 'selectRenderer'], 100);
        $this->listeners[] = $events->attach(ViewEvent::EVENT_RESPONSE, [$this, 'injectResponse'], 100);
    }

    /**
     * Determine if the renderer can load the requested template.
     *
     * @param ViewEvent $e
     * @return bool|TwigRenderer
     */
    public function selectRenderer(ViewEvent $e)
    {
        $model = $e->getModel();
        if (!$model instanceof ViewModel) {
            return false;
        }
        
        if ($this->renderer->canRender($model->getTemplate())) {
            return $this->renderer;
        }
        return false;
    }

    /**
     * Inject the response from the renderer.
     *
     * @param ViewEvent $e
     */
    public function injectResponse(ViewEvent $e)
    {
        $renderer = $e->getRenderer();
        if ($renderer !== $this->renderer) {
            return;
        }
        $result = $e->getResult();
        $response = $e->getResponse();
        $response->setContent($result);
    }

}
