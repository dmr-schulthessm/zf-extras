<?php

namespace ZfExtra\User;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use ZfExtra\Entity\AbstractEntity;
use ZfExtra\Support\ArrayToClassPropertiesTrait;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class UserManager
{

    use ArrayToClassPropertiesTrait;

    /**
     *
     * @var EntityManager
     */
    protected $entityManager;
    
    /**
     *
     * @var string
     */
    protected $entityClass;
    
    /**
     *
     * @var EntityRepository
     */
    protected $repo;

    /**
     * 
     * @param EntityRepository $repo
     */
    public function __construct(EntityManager $entityManager, $entityClass)
    {
        $this->entityManager = $entityManager;
        $this->entityClass = $entityClass;
        $this->repo = $entityManager->getRepository($entityClass);
    }

    /**
     * 
     * @param array $data
     * @param bool $persist
     * @return AbstractEntity
     */
    public function create(array $data = null, $persist = false)
    {
        $entity = new $this->repo->getClassName();
        if (null !== $data) {
            if ($entity instanceof AbstractEntity) {
                $entity->import($data, true);
            } else {
                $this->arrayToClassProperties($data, true);
            }
        }
        
        if ($persist) {
            $this->entityManager->persist($entity);
        }
        return $entity;
    }

    /**
     * 
     * @param UserInterface $user
     */
    public function update(UserInterface $user)
    {
        $this->entityManager->flush($user);
    }
    
    /**
     * 
     * @param int|UserInterface $userOrId
     * @return bool
     */
    public function exists($userOrId)
    {
        return (bool) $this->repo->find($userOrId);
    }
    
    /**
     * 
     * @param array $criteria
     * @return bool
     */
    public function existsBy(array $criteria = array())
    {
        return (bool) $this->repo->findOneBy($criteria);
    }
    
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->repo, $name], $arguments);
    }
}
