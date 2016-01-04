<?php

namespace ZfExtra\View;

use Exception;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\DispatchableInterface;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class LayoutSwitcherListener
{

    public function __invoke(MvcEvent $event)
    {
        $controller = $event->getTarget();
        if (!$controller || !$controller instanceof DispatchableInterface) {
            throw new Exception(__CLASS__ . ': no controller found. Try to set your listener priority to lower value (1).');
        }
        
        $sm = $event->getApplication()->getServiceManager();
        $config = $sm->get('Config');
        $viewLayoutConfig = $config['view_layouts'];
        $matchedRoute = $event->getRouteMatch()->getMatchedRouteName();
        $controllerName = $event->getRouteMatch()->getParam('controller');
        $action = $event->getRouteMatch()->getParam('action');
        
        $controllerClass = get_class($sm->get('ControllerLoader')->get($controllerName));
        $module = array_shift(explode('\\', $controllerClass));
        
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
