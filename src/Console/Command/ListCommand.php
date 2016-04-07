<?php

namespace ZfExtra\Console\Command;

use Symfony\Component\Console\Command\ListCommand as SymfonyListCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class ListCommand extends SymfonyListCommand implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $output->writeln(['', '<comment><info>Zend Framework</info> console commands</comment>', '']);

        /* @var $moduleManager ModuleManager */
        $moduleManager = $this->serviceLocator->getServiceLocator()->get('ModuleManager');
        $console = $this->serviceLocator->getServiceLocator()->get('ConsoleAdapter');
        $help = array();
        foreach ($moduleManager->getLoadedModules() as $module) {
            $parts = explode('\\', get_class($module));
            $modName = array_shift($parts);
            if (is_callable([$module, 'getConsoleUsage'])) {
                $usage = $module->getConsoleUsage($console);
                if (is_array($usage)) {
                    $max = 0;
                    foreach ($usage as $command => $description) {
                        if (is_array($description) || is_numeric($command)) {
                            continue;
                        }

                        if (strlen($command) > $max) {
                            $max = strlen($command);
                        }
                        $help[$command] = $description;
                    }
                }
            }
        }
        
        foreach ($help as $command => $description) {
            $format = sprintf('  <info>%s</info> %s', str_pad($command, $max + 3, ' '), $description);
            $output->writeln($format);
        }
    }

}
