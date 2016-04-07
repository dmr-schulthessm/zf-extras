<?php

namespace ZfExtra\Mvc\Controller\Plugin;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\Controller\PluginManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * @property PluginManager $serviceLocator Description
 * 
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class Orm extends AbstractPlugin implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    /**
     * Returns value from config by $path.
     * 
     * @param string $repositoryClass
     * @param string $entityManager
     * @return EntityManager|EntityRepository
     */
    public function __invoke($repositoryClass = null, $entityManager = 'doctrine.entitymanager.orm_default')
    {
        $entityManager = $this->serviceLocator->getServiceLocator()->get($entityManager);

        if (null == $repositoryClass) {
            return $entityManager;
        }

        return $entityManager->getRepository($repositoryClass);
    }

}
