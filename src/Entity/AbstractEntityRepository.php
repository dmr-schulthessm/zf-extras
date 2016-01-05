<?php

namespace ZfExtra\Entity;

use Doctrine\ORM\EntityRepository;
use Exception;
use ZfExtra\Support\ArrayImportProviderInterface;
use ZfExtra\Support\ArrayToClassPropertiesProviderInterface;

class AbstractEntityRepository extends EntityRepository
{
    /**
     * Finds entity or creates a new new, optionally populating with $data.
     * 
     * @param string $class
     * @param array $criteria
     * @param array $data
     * @param array $mapping
     * @return ArrayImportProviderInterface
     * @throws Exception
     */
    public function findOrCreate(array $criteria, array $data = array(), array $mapping = array())
    {
        $entity = $this->findOneBy($criteria);
        if (!$entity) {
            $class = $this->getClassName();
            $entity = new $class;
            if ($entity instanceof ArrayToClassPropertiesProviderInterface) {
                $entity->arrayToClassProperties($data, false, $mapping);
            } else {
                throw new Exception('Cannot populate new entity instance. It must implement ' . ArrayImportProviderInterface::class . ' interface.');
            }
            $this->_em->persist($entity);
        }
        return $entity;
    }
}
