<?php

namespace Tests\Unit;

use Doctrine\ORM\Query\QueryException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Potievdev\SlimRbac\Component\RbacMiddleware;
use Potievdev\SlimRbac\Exception\CyclicException;
use Potievdev\SlimRbac\Exception\DatabaseException;
use Potievdev\SlimRbac\Exception\InvalidArgumentException;
use Potievdev\SlimRbac\Exception\NotUniqueException;
use Potievdev\SlimRbac\Structure\AuthOptions;

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
     * @throws QueryException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->rbacManager->removeAll();

        $edit = $this->rbacManager->createPermission('edit', 'Edit permission');
        $write = $this->rbacManager->createPermission('write', 'Write permission');

        $moderator = $this->rbacManager->createRole('moderator', 'Moderator role');
        $admin = $this->rbacManager->createRole('admin', 'Admin role');

        $this->rbacManager->attachPermission($moderator, $edit);
        $this->rbacManager->attachPermission($admin, $write);
        $this->rbacManager->attachChildRole($admin, $moderator);

        $this->rbacManager->assign($moderator, self::MODERATOR_USER_ID);
        $this->rbacManager->assign($admin, self::ADMIN_USER_ID);

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
        $middleware = new RbacMiddleware($this->authOptions);
        $request = $this->request->withAttribute($this->authOptions->getUserIdFieldName(), self::ADMIN_USER_ID);
        $response = $middleware->__invoke($request, $this->response, $this->callable);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @throws QueryException
     * @throws InvalidArgumentException
     */
    public function testCheckAccessDeniedCase()
    {
        $middleware = new RbacMiddleware($this->authOptions);
        $request = $this->request->withAttribute($this->authOptions->getUserIdFieldName(), self::MODERATOR_USER_ID);
        $response = $middleware->__invoke($request, $this->response, $this->callable);
        $this->assertEquals(403, $response->getStatusCode());
    }

    /**
     * @throws QueryException
     * @throws InvalidArgumentException
     */
    public function testCheckReadingUserIdFromHeader()
    {
        $authOptions = $this->authOptions;
        $authOptions->setUserIdStorageType(AuthOptions::HEADER_STORAGE_TYPE);
        $middleware = new RbacMiddleware($authOptions);
        $request = $this->request->withHeader($authOptions->getUserIdFieldName(), self::ADMIN_USER_ID);
        $response = $middleware->__invoke($request, $this->response, $this->callable);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @throws QueryException
     * @throws InvalidArgumentException
     */
    public function testCheckReadingUserIdFromCookie()
    {
        $authOptions = $this->authOptions;
        $authOptions->setUserIdStorageType(AuthOptions::COOKIE_STORAGE_TYPE);
        $middleware = new RbacMiddleware($authOptions);
        $request = $this->request->withCookieParams([$authOptions->getUserIdFieldName() => self::ADMIN_USER_ID]);
        $response = $middleware->__invoke($request, $this->response, $this->callable);
        $this->assertEquals(200, $response->getStatusCode());
    }

}
