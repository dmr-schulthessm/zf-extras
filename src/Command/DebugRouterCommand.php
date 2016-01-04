<?php

namespace ZfExtra\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DebugRouterCommand extends AbstractServiceLocatorAwareCommand
{

    protected function configure()
    {
        $this
                ->setName('debug:router')
                ->setDescription('List all routes.')
                ->addOption('dump', 'd', InputOption::VALUE_NONE, 'Dump configured array')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $routes = $this->serviceLocator->getServiceLocator()->get('config.helper')->get('router.routes');
        
        $doDump = $input->getOption('dump');
        if ($doDump) {
            print_r($routes);
            return;
        }
        
        
        $data = $this->fetch($routes);

        $table = new Table($output);
        $table->setHeaders(array(
            'Name', 'Path', 'Type', 'Action', 'Priority', 'May Terminate?'
        ));
        foreach ($data as $route) {
            $table->addRow(array(
                $route['name'], $route['path'], $route['type'], $route['action'], $route['priority'], $route['mayTerminate']
            ));
        }
        $table->render();
    }

    private function fetch(array $routes, $parent = '', $parentPath = '')
    {
        $routeManager = $this->serviceLocator->getServiceLocator()->get('RoutePluginManager');

        $data = array();
        foreach ($routes as $name => $route) {
            $data[] = array(
                'name' => ltrim($parent . '/' . $name, '/'),
                'path' => $parentPath . $route['options']['route'],
                'type' => $route['type'],
                'action' => sprintf('%s::%s', $route['options']['defaults']['controller'], $route['options']['defaults']['action']),
                'priority' => isset($route['priority']) ? (int) $route['priority'] : 0,
                'mayTerminate' => isset($route['may_terminate']) ? (bool) $route['may_terminate'] : true
            );

            if (isset($route['child_routes']) && count($route['child_routes']) > 0) {
                $data = array_merge(
                        $data, 
                        $this->fetch(
                                $route['child_routes'], 
                                ltrim($parent . '/' . $name, '/'), 
                                $parentPath . $route['options']['route']
                            )
                    );
            }
        }
        return $data;
    }

}
