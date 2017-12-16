<?php

namespace Potievdev\SlimRbac\Component;

use Potievdev\SlimRbac\Models\Entity\Permission;
use Potievdev\SlimRbac\Models\Entity\Role;
use Potievdev\SlimRbac\Models\Entity\RoleHierarchy;
use Potievdev\SlimRbac\Models\Entity\RolePermission;
use Potievdev\SlimRbac\Models\Entity\UserRole;

/**
 * Class AuthManager
 * @package Potievdev\SlimRbac\Component
 */
class AuthManager extends BaseComponent
{
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
     * @param Permission $permission
     */
    public function addPermission(Permission $permission)
    {
        $this->entityManager->persist($permission);
        $this->entityManager->flush();
    }

    /**
     * @param Role $role
     */
    public function addRole(Role $role)
    {
        $this->entityManager->persist($role);
        $this->entityManager->flush();
    }

    /**
     * @param Role $role
     * @param Permission $permission
     */
    public function addPermissionToRole(Role $role, Permission $permission)
    {
        $rolePermission = new RolePermission();

        $rolePermission->setPermission($permission);
        $rolePermission->setRole($role);

        $this->entityManager->persist($rolePermission);
        $this->entityManager->flush();
    }

    /**
     * @param Role $parentRole
     * @param Role $childRole
     */
    public function addChildRoleToRole(Role $parentRole, Role $childRole)
    {
        $roleHierarchy = new RoleHierarchy();

        $roleHierarchy->setParentRole($parentRole);
        $roleHierarchy->setChildRole($childRole);

        $this->entityManager->persist($roleHierarchy);
        $this->entityManager->flush();
    }

    /**
     * Assign role to user
     * @param integer $userId
     * @param Role $role
     */
    public function assignRoleToUser($userId, Role $role)
    {
        $userRole = new UserRole();

        $userRole->setUserId($userId);
        $userRole->setRole($role);

        $this->entityManager->persist($userRole);
        $this->entityManager->flush();
    }
}