<?php

namespace Tests\Unit;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Potievdev\SlimRbac\Component\AuthManager;
use Potievdev\SlimRbac\Component\AuthMiddleware;

/**
 * Class for testing AuthMiddleware
 * Class AuthMiddlewareTest
 * @package Tests\Unit
 */
class AuthMiddlewareTest extends BaseTestCase
{
    /** @var callable $callable */
    protected $callable;

    /** @var ServerRequest $request */
    protected $request;

    /** @var Response $response */
    protected $response;

    /**
     * @throws \Potievdev\SlimRbac\Exception\CyclicException
     * @throws \Potievdev\SlimRbac\Exception\DatabaseException
     * @throws \Potievdev\SlimRbac\Exception\NotUniqueException
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function setUp()
    {
        parent::setUp();

        $authManager = new AuthManager($this->authOptions);
        $authManager->removeAll();

        $edit = $authManager->createPermission('edit');
        $edit->setDescription('Edit permission');
        $authManager->addPermission($edit);

        $write = $authManager->createPermission('write');
        $write->setDescription('Write permission');
        $authManager->addPermission($write);

        $moderator = $authManager->createRole('moderator');
        $moderator->setDescription('Moderator role');
        $authManager->addRole($moderator);

        $admin = $authManager->createRole('admin');
        $admin->setDescription('Admin role');
        $authManager->addRole($admin);

        $authManager->addChildPermission($moderator, $edit);
        $authManager->addChildPermission($admin, $write);
        $authManager->addChildRole($admin, $moderator);

        $authManager->assign($moderator, self::MODERATOR_USER_ID);
        $authManager->assign($admin, self::ADMIN_USER_ID);

        $this->callable = function (Request $request, Response $response) {
            return $response;
        };
        $this->request = new ServerRequest('GET', 'write');
        $this->response = new Response();
    }

    /**
     * @throws \Doctrine\ORM\Query\QueryException
     * @throws \Potievdev\SlimRbac\Exception\InvalidArgumentException
     */
    public function testSuccessCase()
    {
        $middleware = new AuthMiddleware($this->authOptions);
        $request = $this->request->withAttribute('userId', self::ADMIN_USER_ID);
        $response = $middleware->__invoke($request, $this->response, $this->callable);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @throws \Doctrine\ORM\Query\QueryException
     * @throws \Potievdev\SlimRbac\Exception\InvalidArgumentException
     */
    public function testFailureCase()
    {
        $middleware = new AuthMiddleware($this->authOptions);
        $request = $this->request->withAttribute('userId', self::MODERATOR_USER_ID);
        $response = $middleware->__invoke($request, $this->response, $this->callable);
        $this->assertEquals(403, $response->getStatusCode());
    }
}
