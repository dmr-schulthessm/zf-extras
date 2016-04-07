<?php
namespace ZfExtra\Asset\Command;

use Exception;
use ReflectionClass;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\ModuleManager\ModuleManager;
use ZfExtra\Asset\AssetManager;
use ZfExtra\Config\ConfigHelper;
use ZfExtra\Console\Command\AbstractServiceLocatorAwareCommand;
use ZfExtra\Console\CommandManager;

/**
 * @property CommandManager $serviceLocator
 */
class InstallAssetsCommand extends AbstractServiceLocatorAwareCommand
{
    
    protected function configure()
    {
        $this
            ->setName('assets:install')
            ->setHelp('Installs module assets into public directory.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var $moduleManager ModuleManager */
        $moduleManager = $this->serviceLocator->getServiceLocator()->get('ModuleManager');
        /* @var $config ConfigHelper */
        $config = $this->serviceLocator->getServiceLocator()->get('config.helper');
        
        $modules = $moduleManager->getLoadedModules();
        
        /* @var $assetManager AssetManager */
        $assetManager = $this->serviceLocator->getServiceLocator()->get('assets');
        
        foreach ($modules as $module) {
            $ref = new ReflectionClass($module);
            $moduleAssetsDir = dirname($ref->getFileName()) . DIRECTORY_SEPARATOR . $config->get('assets.module_assets_dir');
            
            if (!is_dir($moduleAssetsDir)) {
                continue;
            }
            
            if (!is_dir($config->get('assets.install_dir'))) {
                mkdir($config->get('assets.install_dir'), 0755, true);
            }
            
            $moduleNameAlias = strtolower(array_shift(explode('\\', get_class($module))));
            $assetsTargetDir = sprintf('%s/%s', $config->get('assets.install_dir'), $moduleNameAlias);
            
            if (!is_writable($config->get('assets.install_dir'))) {
                throw new Exception('Cannot install module assets. Target dir is not writeable: ' . $config->get('assets.install_dir'));
            }
            
            $output->writeln('Installing assets for <info>' . array_shift(explode('\\', get_class($module))) . '</info> as <comment>symlink</comment>.');
            
            $assetManager->install($moduleAssetsDir, $assetsTargetDir);
        }
    }
}
