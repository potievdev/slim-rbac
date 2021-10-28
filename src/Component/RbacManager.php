<?php

namespace Potievdev\SlimRbac\Component;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Query\QueryException;
use Exception;
use Potievdev\SlimRbac\Exception\CyclicException;
use Potievdev\SlimRbac\Exception\DatabaseException;
use Potievdev\SlimRbac\Exception\NotUniqueException;
use Potievdev\SlimRbac\Models\Entity\Permission;
use Potievdev\SlimRbac\Models\Entity\Role;
use Potievdev\SlimRbac\Models\Entity\RoleHierarchy;
use Potievdev\SlimRbac\Models\Entity\RolePermission;
use Potievdev\SlimRbac\Models\Entity\UserRole;

/**
 * Component for creating and controlling with role and permissions.
 * Class RbacManager
 * @package Potievdev\SlimRbac\Component
 */
class RbacManager extends BaseComponent
{
    /**
     * Deletes all data from database. Use carefully!!!
     *
     * @throws DatabaseException
     * @throws \Doctrine\DBAL\Driver\Exception|\Doctrine\DBAL\Exception
     */
    public function removeAll()
    {
        $pdo = $this->entityManager->getConnection()->getWrappedConnection();
        $pdo->beginTransaction();

        try {
            $pdo->exec('DELETE FROM role_permission WHERE 1 > 0');
            $pdo->exec('DELETE FROM role_hierarchy WHERE 1 > 0');
            $pdo->exec('DELETE FROM permission WHERE 1 > 0');
            $pdo->exec('DELETE FROM user_role WHERE 1 > 0');
            $pdo->exec('DELETE FROM role WHERE 1 > 0');

            $pdo->commit();

        } catch (Exception $e) {
            $pdo->rollBack();
            throw new DatabaseException($e->getMessage());
        }
    }

    /**
     * Creates permission instance with given name and return it.
     * @throws NotUniqueException|DatabaseException
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
            throw new NotUniqueException('Permission with name ' . $permissionMane . ' already created');
        }

        return $permission;
    }

    /**
     * Creates role instance with given name and return it.
     *
     * @throws NotUniqueException|DatabaseException
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
            throw new NotUniqueException('Role with name ' . $roleName . ' already created');
        }

        return $role;
    }

    /**
     * Add permission to role.
     *
     * @throws DatabaseException
     * @throws NotUniqueException
     */
    public function attachPermission(Role $role, Permission $permission)
    {
        $rolePermission = new RolePermission();

        $rolePermission->setPermission($permission);
        $rolePermission->setRole($role);

        try {
            $this->saveEntity($rolePermission);
        } catch (UniqueConstraintViolationException $e) {
            throw new NotUniqueException('Permission ' . $permission->getName() . ' is already assigned to role ' . $role->getName());
        }
    }

    /**
     * Add child role to role.
     *
     * @throws CyclicException
     * @throws DatabaseException
     * @throws NotUniqueException
     * @throws QueryException
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
            throw new NotUniqueException('Child role ' . $childRole->getName() . ' is already has parent role ' . $parentRole->getName());
        }
    }

    /**
     * Assign role to user.
     *
     * @throws NotUniqueException
     * @throws DatabaseException
     */
    public function assign(Role $role, int $userId)
    {
        $userRole = new UserRole();

        $userRole->setUserId($userId);
        $userRole->setRole($role);

        try {
            $this->saveEntity($userRole);
        } catch (UniqueConstraintViolationException $e) {
            throw new NotUniqueException('Role ' . $role->getName() . 'is already assigned to user with identifier ' . $userId);
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
            throw new CyclicException('There detected cyclic line. Role with id = ' . $parentRoleId . ' has child role with id =' . $childRoleId);
        }
    }

    /**
     * Insert or update entity.
     *
     * @throws DatabaseException|UniqueConstraintViolationException
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
