<?php

namespace ZfExtra\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ZfExtra\Config\ConfigHelper;

class DebugConfigCommand extends AbstractServiceLocatorAwareCommand
{

    protected function configure()
    {
        $this
                ->setName('debug:config')
                ->setDescription('Dump config.')
                ->addOption('path', 'p', InputOption::VALUE_OPTIONAL, 'Use a path to key.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var $config ConfigHelper */
        $config = $this->serviceLocator->getServiceLocator()->get('config.helper');

        $path = $input->getOption('path');
        if ($path) {
            return print_r($config->get($path));
        } else {
            return print_r($config->getConfig());
        }
    }

}
