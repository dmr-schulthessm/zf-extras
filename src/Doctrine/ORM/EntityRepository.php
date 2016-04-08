<?php

namespace ZfExtra\Doctrine\ORM;

use Doctrine\ORM\EntityRepository as DoctrineEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Exception;
use ZfExtra\Support\ArrayImportProviderInterface;
use ZfExtra\Support\ArrayToClassPropertiesProviderInterface;

class EntityRepository extends DoctrineEntityRepository
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
            $class = $this->getEntityName();
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
    
    /**
     * High-performant method to remove entities by criteria.
     * 
     * Note, that doctrine's DELETE is NOT cascaded, means, "cascade={"remove"}" will not work here. 
     * You need to have JoinColumn with onDelete="CASCADE" to use that method.
     * 
     * 
     * @see removeBy()
     * @param array $criteria
     * @param string $alias
     * @param string $indexBy
     * @return int
     */
    public function deleteBy(array $criteria, $alias = 'a', $indexBy = null)
    {
        $qb = $this->createQueryBuilder($alias, $indexBy);
        $qb->delete($this->getEntityName(), $alias);
        foreach ($criteria as $column => $value) {
            $qb->andWhere($qb->expr()->eq($alias . '.' . $column, ':' . $column));
        }
        $qb->setParameters($criteria);
        return $qb->getQuery()->execute();
    }
    
    /**
     * Remove all entities.
     * 
     * @return int
     */
    public function deleteAll()
    {
        return $this->removeBy(array());
    }
    
    /**
     * Remove entities by criteria.
     * Honors "cascade={"remove"}" option.
     * 
     * @param array $criteria
     * @param string $alias
     * @param string $indexBy
     * @return int
     */
    public function removeBy(array $criteria, $alias = 'a', $indexBy = null)
    {
        $qb = $this->createQueryBuilder($alias, $indexBy);
        foreach ($criteria as $column => $value) {
            $qb->andWhere($qb->expr()->eq($alias . '.' . $column, ':' . $column));
        }
        $qb->setParameters($criteria);
        $entities = $qb->getQuery()->getResult();
        $total = count($entities);
        foreach ($entities as $entity) {
            $this->_em->remove($entity);
        }
        $this->_em->flush();
        return $total;
    }
    
    /**
     * Remove all entities.
     * 
     * @return int
     */
    public function removeAll()
    {
        return $this->removeBy(array());
    }
    
    /**
     * Remove old version for versioned entities.
     * 
     * @param int $currentRevision
     * @param string $revisionColumn
     * @return int
     */
    public function removeOldRevisions($currentRevision, $revisionColumn)
    {
        $qb = $this->createQueryBuilder('e');
        $qb->delete();
        $qb->where(sprintf('e.%s < :revision', $revisionColumn));
        $qb->setParameter('revision', $currentRevision);
        return $qb->getQuery()->getResult();
    }
    
    /**
     * 
     * @param QueryBuilder $qb
     * @param array $criteria
     * @return QueryBuilder
     */
    public function addCriteriaToWhere(QueryBuilder $qb, array $criteria = []) 
    {
        foreach ($criteria as $column => $value) {
            if (is_null($value)) {
                continue;
            }
            
            if (is_array($value)) {
                $qb->andWhere($qb->expr()->in(sprintf('v.%s', $column), $value));
            } else {
                $qb->andWhere(sprintf('v.%s = :%1$s', $column));
                $qb->setParameter($column, $value);
            }
        }
        return $qb;
    }
}
