<?php

namespace ZfExtra\User;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use ZfExtra\Support\ArrayToClassPropertiesProviderInterface;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class UserManager
{

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
        $entity = new $this->entityClass;
        if (null !== $data) {
            if ($entity instanceof ArrayToClassPropertiesProviderInterface) {
                $entity->arrayToClassProperties($data, true);
            } elseif (method_exists($entity, 'exchangeArray')) {
                $entity->exchangeArray($data);
            } else {
                throw new Exception(sprintf(
                    'Entity "%s" must implement interface "%s" or method "exchangeArray".', get_class($entity), ArrayToClassPropertiesProviderInterface::class
                ));
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
