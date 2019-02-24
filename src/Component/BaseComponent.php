<?php

namespace Potievdev\SlimRbac\Component;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Potievdev\SlimRbac\Exception\DatabaseException;
use Potievdev\SlimRbac\Exception\InvalidArgumentException;
use Potievdev\SlimRbac\Helper\ValidatorHelper;
use Potievdev\SlimRbac\Models\RepositoryRegistry;
use Potievdev\SlimRbac\Structure\AuthOptions;

/**
 * Class BaseComponent
 * @package Potievdev\SlimRbac\Component
 */
class BaseComponent
{
    /** @var  AuthOptions $authOptions */
    protected $authOptions;

    /** @var  EntityManager $entityManager */
    protected $entityManager;

    /** @var  RepositoryRegistry $repositoryRegistry */
    protected $repositoryRegistry;

    /**
     * AuthManager constructor.
     * @param AuthOptions $authOptions
     */
    public function __construct(AuthOptions $authOptions)
    {
        $this->authOptions = $authOptions;
        $this->entityManager = $authOptions->getEntityManager();
        $this->repositoryRegistry = new RepositoryRegistry($this->entityManager);
    }

    /**
     * Insert or update entity
     * @param  object $entity
     * @return object
     * @throws DatabaseException
     * @throws UniqueConstraintViolationException
     */
    protected function saveEntity($entity)
    {
        try {
            $this->entityManager->persist($entity);
            $this->entityManager->flush($entity);
            return $entity;
        } catch (OptimisticLockException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    /**
     * Checks access status
     * @param integer $userId
     * @param string $permissionName
     * @return bool
     * @throws \Exception
     */
    public function checkAccess($userId, $permissionName)
    {
        if (ValidatorHelper::isInteger($userId) == false) {
            throw new InvalidArgumentException('User identifier must be number.');
        }

        /** @var integer $permissionId */
        $permissionId = $this->repositoryRegistry
            ->getPermissionRepository()
            ->getPermissionIdByName($permissionName);

        if (ValidatorHelper::isInteger($permissionId)) {

            /** @var integer[] $rootRoleIds */
            $rootRoleIds = $this->repositoryRegistry
                ->getUserRoleRepository()
                ->getUserRoleIds($userId);

            if (count($rootRoleIds) > 0) {

                /** @var integer[] $allRoleIds */
                $allRoleIds = $this->repositoryRegistry
                    ->getRoleHierarchyRepository()
                    ->getAllRoleIdsHierarchy($rootRoleIds);

                return $this->repositoryRegistry
                    ->getRolePermissionRepository()
                    ->isPermissionAssigned($permissionId, $allRoleIds);
            }
        }

        return false;
    }
}
