<?php

namespace Tests\Unit;

use Doctrine\ORM\Query\QueryException;
use Potievdev\SlimRbac\Exception\CyclicException;
use Potievdev\SlimRbac\Exception\DatabaseException;
use Potievdev\SlimRbac\Exception\InvalidArgumentException;
use Potievdev\SlimRbac\Exception\NotUniqueException;
use Potievdev\SlimRbac\Models\Entity\Permission;
use Potievdev\SlimRbac\Models\Entity\Role;

/**
 * Class RbacManagerTest
 * @package Tests\Unit
 */
class RbacManagerTest extends BaseTestCase
{
    /**
     * @throws CyclicException
     * @throws DatabaseException
     * @throws NotUniqueException
     * @throws QueryException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->rbacManager->removeAll();

        $edit = $this->rbacManager->createPermission('edit');
        $write = $this->rbacManager->createPermission('write');

        $moderator = $this->rbacManager->createRole('moderator');
        $admin = $this->rbacManager->createRole('admin');

        $this->rbacManager->attachPermission($moderator, $edit);
        $this->rbacManager->attachPermission($admin, $write);

        $this->rbacManager->attachChildRole($admin, $moderator);

        $this->rbacManager->assign($moderator, self::MODERATOR_USER_ID);
        $this->rbacManager->assign($admin, self::ADMIN_USER_ID);
    }

    public function successCasesProvider(): array
    {
        return [
            'moderator can edit' => [self::MODERATOR_USER_ID, 'edit'],
            'admin can edit' => [self::ADMIN_USER_ID, 'edit'],
            'admin can write' => [self::ADMIN_USER_ID, 'write'],
        ];
    }

    /**
     * Testing has permission cases.
     * @param integer $userId user id
     * @param string $roleOrPermission role or permission name
     * @throws QueryException
     * @throws InvalidArgumentException
     * @dataProvider successCasesProvider
     */
    public function testCheckAccessSuccessCases(int $userId, string $roleOrPermission): void
    {
        $this->assertTrue($this->rbacManager->checkAccess($userId, $roleOrPermission));
    }

    /**
     * @return array
     */
    public function failCasesProvider(): array
    {
        return [
            'moderator has no write permission' => [self::MODERATOR_USER_ID, 'write'],
            'not existing permission' => [self::ADMIN_USER_ID, 'none_permission'],
            'not existing user id not has permission' => [self::NOT_USER_ID, 'edit'],
            'not existing user id not has role' => [self::NOT_USER_ID, 'admin']
        ];
    }

    /**
     * Testing not have permission cases
     * @param integer $userId user id
     * @param string $roleOrPermission role or permission name
     * @throws QueryException
     * @throws InvalidArgumentException
     * @dataProvider failCasesProvider
     */
    public function testCheckAccessFailureCases(int $userId, string $roleOrPermission): void
    {
        $this->assertFalse($this->rbacManager->checkAccess($userId, $roleOrPermission));
    }

    /**
     * Testing adding not unique permission
     *
     * @throws DatabaseException
     * @throws NotUniqueException
     */
    public function testCheckAddingNotUniquePermission()
    {
        $this->expectException(NotUniqueException::class);
        $this->rbacManager->createPermission('edit');
    }

    /**
     * Testing adding not unique role
     *
     * @throws DatabaseException
     * @throws NotUniqueException
     */
    public function testCheckAddingNonUniqueRole()
    {
        $this->expectException(NotUniqueException::class);
         $this->rbacManager->createRole('moderator');
    }

    /**
     *
     * @throws CyclicException
     * @throws DatabaseException
     * @throws NotUniqueException
     * @throws QueryException
     */
    public function testCheckCyclicException()
    {
        $this->expectException(CyclicException::class);
        $a = $this->rbacManager->createRole('a');
        $b = $this->rbacManager->createRole('b');

        $this->rbacManager->attachChildRole($a, $b);
        $this->rbacManager->attachChildRole($b, $a);
    }

    /**
     * Testing creating permission
     */
    public function testCheckCreatingPermission()
    {
        $permission = $this->repositoryRegistry
            ->getPermissionRepository()
            ->findOneBy(['name' => 'edit']);

        $this->assertTrue($permission instanceof Permission);
    }

    /**
     * Testing creating role
     */
    public function testCheckCreatingRole()
    {
        $role = $this->repositoryRegistry
            ->getRoleRepository()
            ->findOneBy(['name' => 'admin']);

        $this->assertTrue($role instanceof Role);
    }

    /**
     * @throws DatabaseException
     */
    public function testCheckDoubleAssigningPermissionToSameRole()
    {
        $this->expectException(NotUniqueException::class);

        /** @var Role $role */
        $role = $this->repositoryRegistry
            ->getRoleRepository()
            ->findOneBy(['name' => 'admin']);

        /** @var Permission $permission */
        $permission = $this->repositoryRegistry
            ->getPermissionRepository()
            ->findOneBy(['name' => 'write']);

        $this->rbacManager->attachPermission($role, $permission);
    }

    /**
     * @throws QueryException
     * @throws CyclicException
     * @throws DatabaseException
     * @throws NotUniqueException
     *
     */
    public function testCheckAddingSameChildRoleDoubleTime()
    {
        $this->expectException(NotUniqueException::class);

        /** @var Role $parent */
        $parent = $this->repositoryRegistry
            ->getRoleRepository()
            ->findOneBy(['name' => 'admin']);

        /** @var Role $child */
        $child = $this->repositoryRegistry
            ->getRoleRepository()
            ->findOneBy(['name' => 'moderator']);

        $this->rbacManager->attachChildRole($parent, $child);
    }
}
