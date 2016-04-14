<?php

namespace ZfExtra\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ZfExtra\Config\ConfigHelper;

class DebugConfigCommand extends AbstractServiceLocatorAwareCommand
{

    /**
     * 
     */
    protected function configure()
    {
        $this
            ->setName('config')
            ->setDescription('Dump config.')
            ->addOption('path', 'p', InputOption::VALUE_OPTIONAL, 'Use a path to key.')
        ;
    }

    /**
     * 
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var $config ConfigHelper */
        $config = $this->serviceLocator->getServiceLocator()->get('config.helper');

        $path = $input->getOption('path');
        $dumper = new \Symfony\Component\Yaml\Dumper;
        if ($path) {
            $data = $config->get($path);
        } else {
            $data = $config->getConfig();
        }
        echo $dumper->dump($data, 10, 1);
    }

}
