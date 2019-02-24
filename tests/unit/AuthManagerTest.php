<?php

namespace Tests\Unit;

use Potievdev\SlimRbac\Component\AuthManager;
use Potievdev\SlimRbac\Models\Entity\Permission;
use Potievdev\SlimRbac\Models\Entity\Role;

/**
 * Class AuthManagerTest
 * @package Tests\Unit
 */
class AuthManagerTest extends BaseTestCase
{

    /** @var AuthManager $authManager */
    protected $authManager;

    /**
     * @throws \Potievdev\SlimRbac\Exception\CyclicException
     * @throws \Potievdev\SlimRbac\Exception\DatabaseException
     * @throws \Potievdev\SlimRbac\Exception\NotUniqueException
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function setUp()
    {
        parent::setUp();

        $authOptions = $this->createAuthOptions();
        $this->authManager = new AuthManager($authOptions);
        $this->authManager->removeAll();

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
    }

    public function successCasesProvider()
    {

        return [
            'moderator can edit' => [self::MODERATOR_USER_ID, 'edit'],
            'admin can edit' => [self::ADMIN_USER_ID, 'edit'],
            'admin can write' => [self::ADMIN_USER_ID, 'write'],
        ];
    }

    /**
     * Testing has permission cases
     * @param integer $userId user id
     * @param string $roleOrPermission role or permission name
     * @throws \Doctrine\ORM\Query\QueryException
     * @throws \Potievdev\SlimRbac\Exception\InvalidArgumentException
     * @dataProvider successCasesProvider
     */
    public function testCheckAccessSuccessCases($userId, $roleOrPermission)
    {
        $this->assertTrue($this->authManager->checkAccess($userId, $roleOrPermission));
    }

    /**
     * @return array
     */
    public function failCasesProvider()
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
     * @throws \Doctrine\ORM\Query\QueryException
     * @throws \Potievdev\SlimRbac\Exception\InvalidArgumentException
     * @dataProvider failCasesProvider
     */
    public function testCheckAccessFailureCases($userId, $roleOrPermission)
    {
        $this->assertFalse($this->authManager->checkAccess($userId, $roleOrPermission));
    }

    /**
     * Testing adding not unique permission
     * @expectedException \Potievdev\SlimRbac\Exception\NotUniqueException
     * @throws \Potievdev\SlimRbac\Exception\DatabaseException
     * @throws \Potievdev\SlimRbac\Exception\NotUniqueException
     */
    public function testCheckAddingNotUniquePermission()
    {
        $edit = $this->authManager->createPermission('edit');
        $this->authManager->addPermission($edit);
    }

    /**
     * Testing adding not unique role
     * @expectedException \Potievdev\SlimRbac\Exception\NotUniqueException
     * @throws \Potievdev\SlimRbac\Exception\DatabaseException
     * @throws \Potievdev\SlimRbac\Exception\NotUniqueException
     */
    public function testCheckAddingNonUniqueRole()
    {
        $moderator = $this->authManager->createRole('moderator');
        $this->authManager->addRole($moderator);
    }

    /**
     * @expectedException \Potievdev\SlimRbac\Exception\CyclicException
     * @throws \Potievdev\SlimRbac\Exception\CyclicException
     * @throws \Potievdev\SlimRbac\Exception\DatabaseException
     * @throws \Potievdev\SlimRbac\Exception\NotUniqueException
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function testCheckCyclicException()
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
    public function testCheckCreatingPermission()
    {
        $repositoryRegistry = $this->createRepositoryRegistry();
        $permission = $repositoryRegistry
            ->getPermissionRepository()
            ->findOneBy(['name' => 'edit']);

        $this->assertTrue($permission instanceof Permission);
    }

    /**
     * Testing creating role
     */
    public function testCheckCreatingRole()
    {
        $repositoryRegistry = $this->createRepositoryRegistry();

        $role = $repositoryRegistry
            ->getRoleRepository()
            ->findOneBy(['name' => 'admin']);

        $this->assertTrue($role instanceof Role);
    }

    /**
     * @throws \Potievdev\SlimRbac\Exception\DatabaseException
     * @throws \Potievdev\SlimRbac\Exception\NotUniqueException
     * @expectedException \Potievdev\SlimRbac\Exception\NotUniqueException
     */
    public function testCheckDoubleAssigningPermissionToSameRole()
    {
        $repositoryRegistry = $this->createRepositoryRegistry();

        /** @var Role $role */
        $role = $repositoryRegistry
            ->getRoleRepository()
            ->findOneBy(['name' => 'admin']);

        /** @var Permission $permission */
        $permission = $repositoryRegistry
            ->getPermissionRepository()
            ->findOneBy(['name' => 'write']);

        $this->authManager->addChildPermission($role, $permission);
    }

    /**
     * @throws \Doctrine\ORM\Query\QueryException
     * @throws \Potievdev\SlimRbac\Exception\CyclicException
     * @throws \Potievdev\SlimRbac\Exception\DatabaseException
     * @throws \Potievdev\SlimRbac\Exception\NotUniqueException
     * @expectedException \Potievdev\SlimRbac\Exception\NotUniqueException
     */
    public function testCheckAddingSameChildRoleDoubleTime()
    {
        $repositoryRegistry = $this->createRepositoryRegistry();

        /** @var Role $parent */
        $parent = $repositoryRegistry
            ->getRoleRepository()
            ->findOneBy(['name' => 'admin']);

        /** @var Role $child */
        $child = $repositoryRegistry
            ->getRoleRepository()
            ->findOneBy(['name' => 'moderator']);

        $this->authManager->addChildRole($parent, $child);
    }
}
