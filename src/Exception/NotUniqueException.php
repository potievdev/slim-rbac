<?php

namespace Potievdev\SlimRbac\Exception;

/**
 * Class NotUniqueException
 * @package Potievdev\SlimRbac\Exception
 */
class NotUniqueException extends BaseException
{
    public static function permissionWithNameAlreadyCreated(string $permissionName): self
    {
        $e = new self("Permission with given name already created");
        $e->additionalParams = ['permissionName' => $permissionName];

        return $e;
    }

    public static function notUniqueRole(string $roleName): self
    {
        $e = new self("Role with given name already created");
        $e->additionalParams = ['roleName' => $roleName];

        return $e;
    }

    public static function permissionAlreadyAttachedToRole(string $permissionName, string $roleName): self
    {
        $e = new self("Permission already attached to role.");
        $e->additionalParams = ['permissionName' => $permissionName, 'roleName' => $roleName];

        return $e;
    }

    public static function childRoleAlreadyAttachedToGivenParentRole(string $childName, string $parentName): self
    {
        $e = new self("Child role already attached to parent role.");
        $e->additionalParams = ['childRoleName' => $childName, 'parentRoleName' => $parentName];

        return $e;
    }

    public static function roleAlreadyAssignedToUser(string $roleName, string $userId): self
    {
        $e = new self("Role already assigned to user");
        $e->additionalParams = ['roleName' => $roleName, 'userId' => $userId];

        return $e;
    }

}
