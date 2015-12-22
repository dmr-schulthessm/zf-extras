<?php

namespace ZfExtra\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\Controller\PluginManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\RendererInterface;
use Zend\View\View as ZendView;
use Zend\View\ViewEvent;

/**
 * Provides access to view renderer from controller.
 * 
 * @property PluginManager $serviceLocator Controller plugin manager.
 * 
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class Render extends AbstractPlugin implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    /**
     * @return string
     */
    public function __invoke(ViewModel $model)
    {
        /* @var $view ZendView */
        $view = $this->serviceLocator->getServiceLocator()->get('httpviewmanager')->getView();
        $event = new ViewEvent;
        $event->setModel($model);
        $renderers = $view->getEventManager()->trigger(ViewEvent::EVENT_RENDERER, $event, function ($result) {
            return $result instanceof RendererInterface;
        });
        return $renderers->last()->render($model);
    }

}
