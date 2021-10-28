<?php

namespace Tests\Unit;

use Doctrine\ORM\EntityManager;
use Potievdev\SlimRbac\Component\RbacManager;
use Potievdev\SlimRbac\Models\RepositoryRegistry;
use Potievdev\SlimRbac\Structure\RbacManagerOptions;

/**
 * Class BaseTestCase
 * @package Tests\Unit
 */
class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    /** Moderator user identifier */
    const MODERATOR_USER_ID = 1;
    /** Admin user identifier */
    const ADMIN_USER_ID = 2;
    /** User with this id not exists in database */
    const NOT_USER_ID = 3;

    /** @var RbacManager $rbacManager */
    protected $rbacManager;

    /** @var RbacManagerOptions $rbacManagerOptions */
    protected $rbacManagerOptions;

    /** @var RbacManager $rbacManager */
    protected $repositoryRegistry;

    /**
     * Initializing RbacManagerOptions, RbacManager and RbacManagerOptions
     */
    public function setUp(): void
    {
        $entityManager = require __DIR__ . '/../../config/sr-config.php';
        $this->rbacManagerOptions = new RbacManagerOptions($entityManager);
        $this->rbacManager = new RbacManager($this->rbacManagerOptions);
        $this->repositoryRegistry = new RepositoryRegistry($entityManager);
    }
}
