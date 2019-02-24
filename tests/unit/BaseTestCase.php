<?php

namespace Tests\Unit;

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

    /** @var \Doctrine\ORM\EntityManager $entityManager */
    private $entityManager;

    /**
     * @return RepositoryRegistry
     */
    protected function createRepositoryRegistry()
    {
        return new RepositoryRegistry($this->entityManager);
    }

    /**
     * @return AuthOptions
     */
    protected function createAuthOptions()
    {
        $authOptions = new AuthOptions();
        $authOptions->setEntityManager($this->entityManager);
        return $authOptions;
    }

    /**
     * Initializing AuthOptions, AuthManager and AuthOptions
     */
    public function setUp()
    {
        $this->entityManager = require __DIR__ . '/../../config/sr-config.php';
    }
}
