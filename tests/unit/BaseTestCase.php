<?php

namespace Tests\Unit;

use Potievdev\SlimRbac\Component\AuthManager;
use Potievdev\SlimRbac\Component\AuthMiddleware;
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
    protected $entityManager;

    /** @var RepositoryRegistry $entityManager */
    protected $repositoryRegistry;

    /** @var AuthOptions $authOptions */
    protected $authOptions;

    /** @var  AuthManager $authManager */
    protected $authManager;

    /** @var  AuthMiddleware $authMiddleware */
    protected $authMiddleware;


    /**
     * Initializing AuthOptions, AuthManager and AuthOptions
     */
    public function setUp()
    {
        $this->entityManager = require __DIR__ . '/../../config/sr-config.php';

        $this->repositoryRegistry = new RepositoryRegistry($this->entityManager);

        $this->authOptions = new AuthOptions();

        $this->authOptions->setEntityManager($this->entityManager);

        $this->authMiddleware = new AuthMiddleware($this->authOptions);

        $this->authManager = new AuthManager($this->authOptions);

        $this->authManager->removeAll();
    }
}
