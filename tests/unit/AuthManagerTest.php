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

        $this->authManager = new AuthManager($this->authOptions);
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

    /**
     * Testing has permission cases
     * @throws \Doctrine\ORM\Query\QueryException
     * @throws \Potievdev\SlimRbac\Exception\InvalidArgumentException
     */
    public function testAccessTrue()
    {
        $this->assertTrue($this->authManager->checkAccess(self::MODERATOR_USER_ID, 'edit'));
        $this->assertTrue($this->authManager->checkAccess(self::ADMIN_USER_ID, 'edit'));
        $this->assertTrue($this->authManager->checkAccess(self::ADMIN_USER_ID, 'write'));
    }

    /**
     * @return array
     */
    public function failCasesProvider()
    {
        return [
            [self::MODERATOR_USER_ID, 'write'],
            [self::ADMIN_USER_ID, 'none_permission'],
            [self::NOT_USER_ID, 'edit'],
            [self::NOT_USER_ID, 'admin'],
            [self::NOT_USER_ID, 'moderator'],
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
    public function testAccessFalse($userId, $roleOrPermission)
    {
        $this->assertFalse($this->authManager->checkAccess($userId, $roleOrPermission));
    }

    /**
     * Testing adding not unique permission
     * @expectedException \Potievdev\SlimRbac\Exception\NotUniqueException
     * @throws \Potievdev\SlimRbac\Exception\DatabaseException
     * @throws \Potievdev\SlimRbac\Exception\NotUniqueException
     */
    public function testNotUniquePermission()
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
    public function testNonUniqueRole()
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
        $repositoryRegistry = $this->createRepositoryRegistry();
        $permission = $repositoryRegistry
            ->getPermissionRepository()
            ->findOneBy(['name' => 'edit']);

        $this->assertTrue($permission instanceof Permission);
    }

    /**
     * Testing creating role
     */
    public function testCreatingRole()
    {
        $repositoryRegistry = $this->createRepositoryRegistry();

        $role = $repositoryRegistry
            ->getRoleRepository()
            ->findOneBy(['name' => 'admin']);

        $this->assertTrue($role instanceof Role);
    }
}
