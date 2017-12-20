<?php

namespace Tests\Unit;

use Potievdev\SlimRbac\Models\Entity\Permission;
use Potievdev\SlimRbac\Models\Entity\Role;

class AuthManagerTest extends BaseTestCase
{
    /**
     * Testing has permission cases
     */
    public function testAccessTrue()
    {
        $edit = $this->authManager->createPermission('edit');
        $this->authManager->addPermission($edit);

        $write = $this->authManager->createPermission('write');
        $this->authManager->addPermission($write);

        $moderator = $this->authManager->createRole('moderator');
        $this->authManager->addRole($moderator);

        $admin = $this->authManager->createRole('admin');
        $this->authManager->addRole($admin);

        $this->authManager->addChildPermission($moderator, $edit);
        $this->authManager->addChildPermission($admin, $write);
        $this->authManager->addChildRole($admin, $moderator);

        $this->authManager->assign($moderator, self::MODERATOR_USER_ID);
        $this->authManager->assign($admin, self::ADMIN_USER_ID);

        $this->assertTrue($this->authManager->checkAccess(self::MODERATOR_USER_ID, 'edit'));
        $this->assertTrue($this->authManager->checkAccess(self::ADMIN_USER_ID, 'edit'));
        $this->assertTrue($this->authManager->checkAccess(self::ADMIN_USER_ID, 'write'));
    }

    /**
     * Testing not have permission cases
     */
    public function testAccessFalse()
    {
        $edit = $this->authManager->createPermission('edit');
        $this->authManager->addPermission($edit);

        $write = $this->authManager->createPermission('write');
        $this->authManager->addPermission($write);

        $moderator = $this->authManager->createRole('moderator');
        $this->authManager->addRole($moderator);

        $admin = $this->authManager->createRole('admin');
        $this->authManager->addRole($admin);

        $this->authManager->addChildPermission($moderator, $edit);
        $this->authManager->addChildPermission($admin, $write);
        $this->authManager->addChildRole($admin, $moderator);

        $this->assertFalse($this->authManager->checkAccess(self::MODERATOR_USER_ID, 'write'));
        $this->assertFalse($this->authManager->checkAccess(self::ADMIN_USER_ID, 'none_permission'));
        $this->assertFalse($this->authManager->checkAccess(self::NOT_USER_ID, 'edit'));
        $this->assertFalse($this->authManager->checkAccess(self::NOT_USER_ID, 'admin'));
        $this->assertFalse($this->authManager->checkAccess(self::NOT_USER_ID, 'moderator'));
    }

    /**
     * Testing adding not unique permission
     * @expectedException \Potievdev\SlimRbac\Exception\NotUniqueException
     */
    public function testNotUniquePermission()
    {
        $edit = $this->authManager->createPermission('edit');
        $this->authManager->addPermission($edit);

        $edit = $this->authManager->createPermission('edit');
        $this->authManager->addPermission($edit);
    }

    /**
     * Testing adding not unique role
     * @expectedException \Potievdev\SlimRbac\Exception\NotUniqueException
     */
    public function testNonUniqueRole()
    {
        $moderator = $this->authManager->createRole('moderator');
        $this->authManager->addRole($moderator);

        $moderator = $this->authManager->createRole('moderator');
        $this->authManager->addRole($moderator);
    }

    /**
     * @expectedException \Potievdev\SlimRbac\Exception\CyclicException
     */
    public function testCyclicException()
    {
        $a = $this->authManager->createRole('a');
        $b = $this->authManager->createRole('b');

        $this->authManager->addRole($a);
        $this->authManager->addRole($b);

        $this->authManager->addChildRole($a, $b);
        $this->authManager->addChildRole($b, $a);
    }

    /**
     * Testing creating permission
     */
    public function testCreatingPermission()
    {
        $permissionName = 'edit';

        $edit = $this->authManager->createPermission($permissionName);
        $this->authManager->addPermission($edit);

        $permission = $this->repositoryRegistry
            ->getPermissionRepository()
            ->findOneBy(['name' => $permissionName]);

        $this->assertTrue($permission instanceof Permission);
    }

    /**
     * Testing creating role
     */
    public function testCreatingRole()
    {
        $roleName = 'admin';

        $admin = $this->authManager->createRole($roleName);
        $this->authManager->addRole($admin);

        $role = $this->repositoryRegistry
            ->getRoleRepository()
            ->findOneBy(['name' => $roleName]);

        $this->assertTrue($role instanceof Role);
    }
}