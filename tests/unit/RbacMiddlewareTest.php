<?php

namespace Tests\Unit;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\QueryException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Potievdev\SlimRbac\Component\Config\RbacConfig;
use Potievdev\SlimRbac\Component\RbacContainer;
use Potievdev\SlimRbac\Exception\ConfigNotFoundException;
use Potievdev\SlimRbac\Exception\CyclicException;
use Potievdev\SlimRbac\Exception\DatabaseException;
use Potievdev\SlimRbac\Exception\InvalidArgumentException;
use Potievdev\SlimRbac\Exception\NotUniqueException;

/**
 * Class for testing RbacMiddleware
 * Class RbacMiddlewareTest
 * @package Tests\Unit
 */
class RbacMiddlewareTest extends BaseTestCase
{
    /** @var callable $callable */
    protected $callable;

    /** @var ServerRequest $request */
    protected $request;

    /** @var Response $response */
    protected $response;

    /**
     * @throws CyclicException
     * @throws DatabaseException
     * @throws NotUniqueException
     * @throws QueryException|ORMException
     */
    public function setUp(): void
    {
        parent::setUp();

        $edit = $this->rbacManager->createPermission('edit', 'Edit permission');
        $write = $this->rbacManager->createPermission('write', 'Write permission');

        $moderator = $this->rbacManager->createRole('moderator', 'Moderator role');
        $admin = $this->rbacManager->createRole('admin', 'Admin role');

        $this->rbacManager->attachPermission($moderator, $edit);
        $this->rbacManager->attachPermission($admin, $write);
        $this->rbacManager->attachChildRole($admin, $moderator);

        $this->rbacManager->assignRoleToUser($moderator, self::MODERATOR_USER_ID);
        $this->rbacManager->assignRoleToUser($admin, self::ADMIN_USER_ID);

        $this->callable = function (Request $request, Response $response) {
            return $response;
        };
        $this->request = new ServerRequest('GET', 'write');
        $this->response = new Response();
    }

    /**
     * @throws QueryException
     * @throws InvalidArgumentException
     */
    public function testCheckAccessSuccessCase()
    {
        $middleware = (new RbacContainer())->getRbacMiddleware();
        $request = $this->request->withAttribute('userId', self::ADMIN_USER_ID);
        $response = $middleware($request, $this->response, $this->callable);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @throws QueryException
     * @throws InvalidArgumentException
     */
    public function testCheckAccessDeniedCase()
    {
        $middleware = (new RbacContainer())->getRbacMiddleware();
        $request = $this->request->withAttribute('userId', self::MODERATOR_USER_ID);
        $response = $middleware($request, $this->response, $this->callable);
        $this->assertEquals(403, $response->getStatusCode());
    }

    /**
     * @throws QueryException
     * @throws InvalidArgumentException
     * @throws ConfigNotFoundException
     */
    public function testCheckReadingUserIdFromHeader()
    {
        $middleware = (new RbacContainer($this->createRbacConfig(RbacConfig::HEADER_RESOURCE_TYPE)))
            ->getRbacMiddleware();
        $request = $this->request->withHeader('userId', self::ADMIN_USER_ID);
        $response = $middleware($request, $this->response, $this->callable);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @throws QueryException
     * @throws InvalidArgumentException
     * @throws ConfigNotFoundException
     */
    public function testCheckReadingUserIdFromCookie()
    {
        $middleware = (new RbacContainer($this->createRbacConfig(RbacConfig::COOKIE_RESOURCE_TYPE)))
            ->getRbacMiddleware();
        $request = $this->request->withCookieParams(['userId' => self::ADMIN_USER_ID]);
        $response = $middleware($request, $this->response, $this->callable);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @throws ConfigNotFoundException
     */
    private function createRbacConfig(?string $resourceTypeId): RbacConfig
    {
        $rbacConfig = RbacConfig::createFromConfigFile();

        return new RbacConfig(
            $rbacConfig->getDatabaseDriver(),
            $rbacConfig->getDatabaseHost(),
            $rbacConfig->getDatabaseUser(),
            $rbacConfig->getDatabasePassword(),
            $rbacConfig->getDatabasePort(),
            $rbacConfig->getDatabaseName(),
            $rbacConfig->getDatabaseCharset(),
            $rbacConfig->getUserIdFieldName(),
            $resourceTypeId ?? $rbacConfig->getUserIdResourceType()
        );
    }

}
