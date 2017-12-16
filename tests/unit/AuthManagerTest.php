<?php

namespace Tests\Unit;

use PHPUnit_Framework_TestCase;
use Potievdev\SlimRbac\Component\AuthManager;
use Potievdev\SlimRbac\Models\Entity\Permission;
use Potievdev\SlimRbac\Models\Entity\Role;
use Potievdev\SlimRbac\Structure\AuthManagerOptions;

class AuthManagerTest extends PHPUnit_Framework_TestCase
{
    /** Moderator user identifier */
    const MODERATOR_USER_ID = 1;
    /** Admin user identifier */
    const ADMIN_USER_ID = 2;
    /** User with this id not exists in database */
    const NOT_USER_ID = 3;

    /** @var  AuthManager $authManager */
    protected $authManager;

    public function setUp()
    {
        $helperSet = require __DIR__ . '/../../cli-config.php';

        /** @var \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper $entityManagerHelper */
        $entityManagerHelper = $helperSet->get('em');

        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = $entityManagerHelper->getEntityManager();

        $authManagerOptions = new AuthManagerOptions();
        $authManagerOptions->setEntityManager($entityManager);

        $this->authManager = new AuthManager($authManagerOptions);

        $this->authManager->removeAll();

        $edit = new Permission();
        $edit->setName('edit');
        $this->authManager->addPermission($edit);

        $write = new Permission();
        $write->setName('write');
        $this->authManager->addPermission($write);

        $moderator = new Role();
        $moderator->setName('moderator');
        $this->authManager->addRole($moderator);

        $admin = new Role();
        $admin->setName('admin');
        $this->authManager->addRole($admin);

        $this->authManager->addPermissionToRole($moderator, $edit);
        $this->authManager->addPermissionToRole($admin, $write);
        $this->authManager->addChildRoleToRole($admin, $moderator);

        $this->authManager->assignRoleToUser(self::MODERATOR_USER_ID, $moderator);
        $this->authManager->assignRoleToUser(self::ADMIN_USER_ID, $admin);
    }

    /**
     * Testing has permission cases
     */
    public function testHasPermission()
    {
        $this->assertTrue($this->authManager->can(self::ADMIN_USER_ID, 'edit'));
        $this->assertTrue($this->authManager->can(self::ADMIN_USER_ID, 'write'));
    }

    /**
     * Testing not have permission cases
     */
    public function testHasNotPermission()
    {
        $this->assertFalse($this->authManager->can(self::MODERATOR_USER_ID, 'write'));
        $this->assertFalse($this->authManager->can(self::ADMIN_USER_ID, 'none_permission'));
        $this->assertFalse($this->authManager->can(self::MODERATOR_USER_ID, 'none_permission'));
        $this->assertFalse($this->authManager->can(self::NOT_USER_ID, 'edit'));
    }
}