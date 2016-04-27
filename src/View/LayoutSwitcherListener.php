<?php

namespace ZfExtra\View;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;
use ZfExtra\Config\ConfigHelper;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class LayoutSwitcherListener extends AbstractListenerAggregate
{

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_RENDER, [$this, 'onRender']);
    }

    public function onRender(MvcEvent $event)
    {
        $routeMatch = $event->getRouteMatch();
        if (!$routeMatch) {
            return;
        }

        /* @var $serviceManager ServiceManager */
        $serviceManager = $event->getApplication()->getServiceManager();
        /* @var $config ConfigHelper */
        $config = $serviceManager->get(ConfigHelper::class);
        $viewLayoutConfig = $config->get('view_layouts');
        $matchedRoute = $routeMatch->getMatchedRouteName();
        $controllerName = $routeMatch->getParam('controller');
        $action = $event->getRouteMatch()->getParam('action');

        $controller = $serviceManager->get('ControllerLoader')->get($controllerName);
        $parts = explode('\\', get_class($controller));
        $module = array_shift($parts);

        $layout = $this->detectLayout($viewLayoutConfig, $matchedRoute, $module, $controllerName, $action);
        $controller->layout($layout);
    }

    public function detectLayout(array $viewLayoutConfig, $matchedRoute = null, $module = null, $controller = null, $action = null)
    {
        foreach ($viewLayoutConfig['order'] as $source) {
            if (!isset($viewLayoutConfig['layouts'][$source])) {
                continue;
            }

            foreach ($viewLayoutConfig['layouts'][$source] as $condition => $layout) {
                switch ($source) {
                    case 'action':
                        list ($_controller, $_action) = explode('::', $condition);
                        if ($_controller === $controller && $_action === $action) {
                            return $layout;
                        }
                        break;
                    case 'controller';
                        if ($condition === $controller) {
                            return $layout;
                        }
                        break;
                    case 'module':
                        if ($condition === $module) {
                            return $layout;
                        }
                        break;
                    case 'route':
                        if (preg_match('/' . $condition . '/', $matchedRoute)) {
                            return $layout;
                        }
                        break;
                }
            }
        }
    }

}
