<?php

namespace ZfExtra\Command;

use ReflectionClass;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

class DebugServiceCommand extends AbstractServiceLocatorAwareCommand
{

    protected function configure()
    {
        $this
                ->setName('debug:services')
                ->addOption('manager', 'm', InputOption::VALUE_OPTIONAL, 'From specific service manager', null)
                ->setDescription('List all configured services.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var $serviceManager ServiceManager */
        $sm = $input->getOption('manager');
        if ($sm) {
            $serviceManager = $this->serviceLocator->getServiceLocator()->get($sm);
        } else {
            $serviceManager = $this->serviceLocator->getServiceLocator();
        }

        $this->render($serviceManager, $output);
    }

    protected function getServices(ServiceLocatorInterface $serviceManager)
    {
        $props = [
            'invokableClasses',
            'factories',
            'abstractFactories',
            'initializers',
            'delegators',
            'shared',
            'aliases'
        ];
        $ref = new ReflectionClass($serviceManager);

        $result = array();
        foreach ($props as $prop) {
            $refProp = $ref->getProperty($prop);
            $refProp->setAccessible(true);
            $values = $refProp->getValue($serviceManager);
            foreach ($values as $key => &$value) {
                if (is_object($value)) {
                    $value = get_class($value);
                }

                if (is_array($value)) {
                    foreach ($value as &$subval) {
                        if (is_object($subval)) {
                            $subval = get_class($subval);
                        }
                    }
                }
            }
            $result[$prop] = $values;
        }
        return $result;
    }

    protected function render(ServiceLocatorInterface $serviceManager, OutputInterface $output)
    {
        $services = $this->getServices($serviceManager);

        $output->writeln(sprintf('Services from <info>%s</info>', get_class($serviceManager)));
        $output->writeln('');

        // invokables
        if (count($services['invokableClasses']) > 0) {
            $output->writeln('<comment>Invokables:</comment>');
            $table = new Table($output);
            $table->setHeaders(['Name', 'Class', 'Shared', 'Aliases']);
            foreach ($services['invokableClasses'] as $name => $class) {
                $table->addRow(array(
                    $name, $class, (int) $services['shared'][$name], $this->getServiceAliases($name, $services['aliases'])
                ));
            }
            $table->render();
            $output->writeln('');
        }

        // factories
        if (count($services['factories']) > 0) {
            $output->writeln('<comment>Factories:</comment>');
            $table = new Table($output);
            $table->setHeaders(['Name', 'Class', 'Shared', 'Aliases']);
            foreach ($services['factories'] as $name => $class) {
                $table->addRow(array(
                    $name, $class, (int) $services['shared'][$name], $this->getServiceAliases($name, $services['aliases'])
                ));
            }
            $table->render();
            $output->writeln('');
        }

        // abstract factories
        if (count($services['abstractFactories']) > 0) {
            $output->writeln('<comment>Abstract Factories:</comment>');
            $table = new Table($output);
            $table->setHeaders(['Name']);
            foreach ($services['abstractFactories'] as $class) {
                $table->addRow([$class]);
            }
            $table->render();
            $output->writeln('');
        }

        // delegators
        if (count($services['delegators']) > 0) {
            $output->writeln('<comment>Delegators:</comment>');
            $table = new Table($output);
            $table->setHeaders(['Service', 'Delegators']);
            foreach ($services['delegators'] as $service => $delegators) {
                $table->addRow([$service, join(', ', $delegators)]);
            }
            $table->render();
            $output->writeln('');
        }

        // initializers
        if (count($services['initializers']) > 0) {
            $output->writeln('<comment>Initializers:</comment>');
            $table = new Table($output);
            $table->setHeaders(['Name']);
            foreach ($services['initializers'] as $initializer) {
                if (is_array($initializer)) {
                    $initializer = join('::', $initializer);
                }
                $table->addRow([$initializer]);
            }
            $table->render();
            $output->writeln('');
        }
    }

    protected function getServiceAliases($service, $aliases)
    {
        $result = [];
        foreach ($aliases as $alias => $serviceName) {
            if ($service == $serviceName) {
                $result[] = $alias;
            }
        }
        return join(', ', $result);
    }

}
