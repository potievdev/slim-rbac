<?php

namespace Potievdev\SlimRbac\Component;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\QueryException;
use Potievdev\SlimRbac\Exception\CyclicException;
use Potievdev\SlimRbac\Exception\DatabaseException;
use Potievdev\SlimRbac\Exception\NotUniqueException;
use Potievdev\SlimRbac\Models\Entity\Permission;
use Potievdev\SlimRbac\Models\Entity\Role;
use Potievdev\SlimRbac\Models\Entity\RoleHierarchy;
use Potievdev\SlimRbac\Models\Entity\RolePermission;
use Potievdev\SlimRbac\Models\Entity\UserRole;
use Potievdev\SlimRbac\Models\RepositoryRegistry;

/**
 * Component for creating and controlling with role and permissions.
 * Class RbacManager
 * @package Potievdev\SlimRbac\Component
 */
class RbacManager
{
    /** @var EntityManager */
    private $entityManager;

    /** @var RepositoryRegistry */
    private $repositoryRegistry;

    /**
     * @param EntityManager $entityManager
     * @param RepositoryRegistry $repositoryRegistry
     */
    public function __construct(EntityManager $entityManager, RepositoryRegistry $repositoryRegistry)
    {
        $this->entityManager = $entityManager;
        $this->repositoryRegistry = $repositoryRegistry;
    }

    /**
     * Creates permission instance with given name and return it.
     * @throws NotUniqueException|DatabaseException|ORMException
     */
    public function createPermission(string $permissionMane, ?string $description = null): Permission
    {
        $permission = new Permission();
        $permission->setName($permissionMane);

        if (isset($description)) {
            $permission->setDescription($description);
        }

        try {
            $this->saveEntity($permission);
        } catch (UniqueConstraintViolationException $e) {
            throw NotUniqueException::permissionWithNameAlreadyCreated($permissionMane);
        }

        return $permission;
    }

    /**
     * Creates role instance with given name and return it.
     *
     * @throws NotUniqueException|DatabaseException|ORMException
     */
    public function createRole(string $roleName, ?string $description = null): Role
    {
        $role = new Role();
        $role->setName($roleName);

        if (isset($description)) {
            $role->setDescription($description);
        }

        try {
            $this->saveEntity($role);
        } catch (UniqueConstraintViolationException $e) {
            throw NotUniqueException::notUniqueRole($roleName);
        }

        return $role;
    }

    /**
     * Add permission to role.
     *
     * @throws DatabaseException
     * @throws NotUniqueException|ORMException
     */
    public function attachPermission(Role $role, Permission $permission)
    {
        $rolePermission = new RolePermission();

        $rolePermission->setPermission($permission);
        $rolePermission->setRole($role);

        try {
            $this->saveEntity($rolePermission);
        } catch (UniqueConstraintViolationException $e) {
            throw NotUniqueException::permissionAlreadyAttachedToRole($permission->getName(), $role->getName());
        }
    }

    /**
     * Add child role to role.
     *
     * @throws CyclicException
     * @throws DatabaseException
     * @throws NotUniqueException
     * @throws QueryException|ORMException
     */
    public function attachChildRole(Role $parentRole, Role $childRole)
    {
        $roleHierarchy = new RoleHierarchy();

        $roleHierarchy->setParentRole($parentRole);
        $roleHierarchy->setChildRole($childRole);

        $this->checkForCyclicHierarchy($childRole->getId(), $parentRole->getId());

        try {
            $this->saveEntity($roleHierarchy);
        }  catch (UniqueConstraintViolationException $e) {
            throw NotUniqueException::childRoleAlreadyAttachedToGivenParentRole(
                $childRole->getName(),
                $parentRole->getName()
            );
        }
    }

    /**
     * Assign role to user.
     *
     * @throws NotUniqueException
     * @throws DatabaseException|ORMException
     */
    public function assignRoleToUser(Role $role, int $userId)
    {
        $userRole = new UserRole();

        $userRole->setUserId($userId);
        $userRole->setRole($role);

        try {
            $this->saveEntity($userRole);
        } catch (UniqueConstraintViolationException $e) {
            throw NotUniqueException::roleAlreadyAssignedToUser($role->getName(), $userId);
        }
    }

    /**
     * Checking hierarchy cyclic line.
     *
     * @throws CyclicException
     * @throws QueryException
     */
    private function checkForCyclicHierarchy(int $parentRoleId, int $childRoleId): void
    {
        $result = $this->repositoryRegistry
            ->getRoleHierarchyRepository()
            ->hasChildRoleId($parentRoleId, $childRoleId);

        if ($result === true) {
            throw CyclicException::cycleDetected($parentRoleId, $childRoleId);
        }
    }

    /**
     * Insert or update entity.
     *
     * @throws DatabaseException|ORMException
     */
    private function saveEntity(object $entity): void
    {
        try {
            $this->entityManager->persist($entity);
            $this->entityManager->flush($entity);
        } catch (OptimisticLockException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

}
