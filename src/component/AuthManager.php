<?php

namespace Potievdev\SlimRbac\Component;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Potievdev\SlimRbac\Exception\CyclicException;
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

        if ($result == true) {
            throw new CyclicException('There detected cyclic line. Role with id = ' . $parentRoleId . ' has child role whit id =' . $childRoleId);
        }
    }

    /**
     * Truncates all tables
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
            $pdo->exec('SET FOREIGN_KEY_CHECKS=0');

            $pdo->commit();

        } catch (\Exception $e) {
            $pdo->rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Save permission in database
     * @param Permission $permission
     * @throws NotUniqueException
     */
    public function addPermission(Permission $permission)
    {
        try {
            $this->entityManager->persist($permission);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw new NotUniqueException('Permission with name ' . $permission->getName() . ' already created');
        }
    }

    /**
     * Save role in database
     * @param Role $role
     * @throws NotUniqueException
     */
    public function addRole(Role $role)
    {
        try {
            $this->entityManager->persist($role);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw new NotUniqueException('Role with name ' . $role->getName() . ' already created');
        }
    }

    /**
     * Add permission to role
     * @param Role $role
     * @param Permission $permission
     * @throws NotUniqueException
     */
    public function addPermissionToRole(Role $role, Permission $permission)
    {
        $rolePermission = new RolePermission();

        $rolePermission->setPermission($permission);
        $rolePermission->setRole($role);

        try {
            $this->entityManager->persist($rolePermission);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw new NotUniqueException('Permission ' . $permission->getName() . ' is already assigned to role ' . $role->getName());
        }
    }

    /**
     * Add child role to role
     * @param Role $parentRole
     * @param Role $childRole
     * @throws NotUniqueException
     */
    public function addChildRoleToRole(Role $parentRole, Role $childRole)
    {
        $roleHierarchy = new RoleHierarchy();

        $roleHierarchy->setParentRole($parentRole);
        $roleHierarchy->setChildRole($childRole);

        $this->checkForCyclicHierarchy($childRole->getId(), $parentRole->getId());

        try {
            $this->entityManager->persist($roleHierarchy);
            $this->entityManager->flush();
        }  catch (UniqueConstraintViolationException $e) {
            throw new NotUniqueException('Child role ' . $childRole->getName() . ' is already has parent role ' . $parentRole->getName());
        }
    }

    /**
     * Assign role to user
     * @param integer $userId
     * @param Role $role
     * @throws NotUniqueException
     */
    public function assignRoleToUser($userId, Role $role)
    {
        $userRole = new UserRole();

        $userRole->setUserId($userId);
        $userRole->setRole($role);

        try {
            $this->entityManager->persist($userRole);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw new NotUniqueException('Role ' . $role->getName() . 'is already assigned to user with identifier ' . $userId);
        }
    }
}