<?php

namespace Tests\Unit;

use Exception;
use Potievdev\SlimRbac\Component\RbacAccessChecker;
use Potievdev\SlimRbac\Component\RbacContainer;
use Potievdev\SlimRbac\Component\RbacManager;
use Potievdev\SlimRbac\Exception\DatabaseException;
use Potievdev\SlimRbac\Models\RepositoryRegistry;

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

    /** @var RepositoryRegistry $repositoryRegistry */
    protected $repositoryRegistry;

    /** @var RbacAccessChecker $accessChecker */
    protected $accessChecker;

    protected $rbacContainer;

    /**
     * Initializing RbacManager.
     * @throws DatabaseException
     */
    public function setUp(): void
    {
        $this->rbacContainer = new RbacContainer();
        $this->rbacManager = $this->rbacContainer->getRbacManager();
        $this->repositoryRegistry = $this->rbacContainer->getInnerContainer()->get('repositoryRegistry');
        $this->accessChecker = $this->rbacContainer->getInnerContainer()->get('accessChecker');
        $this->clearDatabase();
    }

    /**
     * @throws DatabaseException
     */
    private function clearDatabase(): void
    {
        $pdo = $this->rbacContainer->getInnerContainer()
            ->get('entityManager')
            ->getConnection()
            ->getNativeConnection();

        $pdo->beginTransaction();

        try {
            $pdo->exec('DELETE FROM role_permission WHERE 1 > 0');
            $pdo->exec('DELETE FROM role_hierarchy WHERE 1 > 0');
            $pdo->exec('DELETE FROM permission WHERE 1 > 0');
            $pdo->exec('DELETE FROM user_role WHERE 1 > 0');
            $pdo->exec('DELETE FROM role WHERE 1 > 0');

            $pdo->commit();

        } catch (Exception $e) {
            $pdo->rollBack();
            throw new DatabaseException($e->getMessage());
        }
    }
}
