<?php

namespace ZfExtra\Log\Factory;

use Zend\Log\Logger;
use Zend\Log\Writer\WriterInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfExtra\Config\ConfigHelper;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@muehlemann-popp.ch>
 */
class ApplicationLoggerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get(ConfigHelper::class)->get('logger');
        $logger = new Logger;

        foreach ($config['writers'] as $writerService) {
            /* @var $writer WriterInterface */
            $writer = $serviceLocator->get($writerService);
            $writer->setFormatter($serviceLocator->get($config['formatter']));
            $logger->addWriter($writer);

            foreach ($config['filters'] as $filterService) {
                $filter = $serviceLocator->get($filterService);
                $writer->addFilter($filter);
            }
        }

        return $logger;
    }

}
