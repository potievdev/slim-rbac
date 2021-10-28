<?php

namespace Tests\Unit;

use Doctrine\ORM\EntityManager;
use Potievdev\SlimRbac\Component\RbacManager;
use Potievdev\SlimRbac\Models\RepositoryRegistry;
use Potievdev\SlimRbac\Structure\AuthOptions;

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

    /** @var AuthOptions $authOptions */
    protected $authOptions;

    /** @var RbacManager $rbacManager */
    protected $repositoryRegistry;

    /** @var EntityManager $entityManager */
    private $entityManager;

    /**
     * Initializing AuthOptions, RbacManager and AuthOptions
     */
    public function setUp(): void
    {
        $this->entityManager = require __DIR__ . '/../../config/sr-config.php';
        $this->authOptions = new AuthOptions($this->entityManager);
        $this->rbacManager = new RbacManager($this->authOptions);
        $this->repositoryRegistry = new RepositoryRegistry($this->entityManager);
    }
}
