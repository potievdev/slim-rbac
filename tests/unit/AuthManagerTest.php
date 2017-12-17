<?php

namespace Tests\Unit;

use PHPUnit_Framework_TestCase;
use Potievdev\SlimRbac\Component\AuthManager;
use Potievdev\SlimRbac\Component\AuthMiddleware;
use Potievdev\SlimRbac\Models\Entity\Permission;
use Potievdev\SlimRbac\Models\Entity\Role;
use Potievdev\SlimRbac\Structure\AuthOptions;

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

    /** @var  AuthMiddleware $authMiddleware */
    protected $authMiddleware;

    /**
     * Configuring testing environment
     */
    public function setUp()
    {
        $helperSet = require __DIR__ . '/../../config/cli-config.php';

        /** @var \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper $entityManagerHelper */
        $entityManagerHelper = $helperSet->get('em');

        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = $entityManagerHelper->getEntityManager();

        $authOptions = new AuthOptions();
        $authOptions->setEntityManager($entityManager);

        $this->authMiddleware = new AuthMiddleware($authOptions);

        $this->authManager = new AuthManager($authOptions);

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
    public function testAccessTrue()
    {
        $this->assertTrue($this->authMiddleware->checkAccess(self::ADMIN_USER_ID, 'edit'));
        $this->assertTrue($this->authMiddleware->checkAccess(self::ADMIN_USER_ID, 'write'));
    }

    /**
     * Testing not have permission cases
     */
    public function testAccessFalse()
    {
        $this->assertFalse($this->authMiddleware->checkAccess(self::MODERATOR_USER_ID, 'write'));
        $this->assertFalse($this->authMiddleware->checkAccess(self::ADMIN_USER_ID, 'none_permission'));
        $this->assertFalse($this->authMiddleware->checkAccess(self::MODERATOR_USER_ID, 'none_permission'));
        $this->assertFalse($this->authMiddleware->checkAccess(self::NOT_USER_ID, 'edit'));
    }
}