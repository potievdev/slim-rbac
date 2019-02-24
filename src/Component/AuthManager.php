<?php

namespace Potievdev\SlimRbac\Component;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
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
 * Class AuthManager
 * @package Potievdev\SlimRbac\Component
 */
class AuthManager extends BaseComponent
{
    /**
     * Checking hierarchy cyclic line
     * @param integer $parentRoleId
     * @param integer $childRoleId
     * @throws CyclicException
     */
    private function checkForCyclicHierarchy($parentRoleId, $childRoleId)
    {
        $result = $this->repositoryRegistry
            ->getRoleHierarchyRepository()
            ->hasChildRoleId($parentRoleId, $childRoleId);

        if ($result === true) {
            throw new CyclicException('There detected cyclic line. Role with id = ' . $parentRoleId . ' has child role whit id =' . $childRoleId);
        }
    }

    /**
     * Truncates all tables
     * @throws DatabaseException
     */
    public function removeAll()
    {
        $pdo = $this->entityManager->getConnection()->getWrappedConnection();
        $pdo->beginTransaction();

        try {

            $pdo->exec('SET FOREIGN_KEY_CHECKS=0');
            $pdo->exec('TRUNCATE role_permission');
            $pdo->exec('TRUNCATE role_hierarchy');
            $pdo->exec('TRUNCATE role');
            $pdo->exec('TRUNCATE permission');
            $pdo->exec('TRUNCATE user_role');
            $pdo->exec('SET FOREIGN_KEY_CHECKS=1');

            $pdo->commit();

        } catch (\Exception $e) {
            $pdo->rollBack();
            throw new DatabaseException($e->getMessage());
        }
    }

    /**
     * Creates permission instance with given name and return it
     * @param string $permissionMane
     * @return Permission
     */
    public function createPermission($permissionMane)
    {
        $permission = new Permission();
        $permission->setName($permissionMane);

        return $permission;
    }

    /**
     * Creates role instance with given name and return it
     * @param string $roleName
     * @return Role
     */
    public function createRole($roleName)
    {
        $role = new Role();
        $role->setName($roleName);

        return $role;
    }

    /**
     * Save permission in database
     * @param Permission $permission
     * @throws NotUniqueException
     * @throws DatabaseException
     */
    public function addPermission(Permission $permission)
    {
        try {
            $this->saveEntity($permission);
        } catch (UniqueConstraintViolationException $e) {
            throw new NotUniqueException('Permission with name ' . $permission->getName() . ' already created');
        }
    }

    /**
     * Save role in database
     * @param Role $role
     * @throws NotUniqueException
     * @throws DatabaseException
     */
    public function addRole(Role $role)
    {
        try {
            $this->saveEntity($role);
        } catch (UniqueConstraintViolationException $e) {
            throw new NotUniqueException('Role with name ' . $role->getName() . ' already created');
        }
    }

    /**
     * Add permission to role
     * @param Role $role
     * @param Permission $permission
     * @throws DatabaseException
     * @throws NotUniqueException
     */
    public function addChildPermission(Role $role, Permission $permission)
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
     * Add child role to role
     * @param Role $parentRole
     * @param Role $childRole
     * @throws CyclicException
     * @throws DatabaseException
     * @throws NotUniqueException
     */
    public function addChildRole(Role $parentRole, Role $childRole)
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
     * Assign role to user
     * @param Role $role
     * @param integer $userId
     * @throws NotUniqueException
     * @throws DatabaseException
     */
    public function assign(Role $role, $userId)
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
}