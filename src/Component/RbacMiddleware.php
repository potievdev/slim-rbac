<?php

namespace Potievdev\SlimRbac\Component;

use Doctrine\ORM\Query\QueryException;
use Potievdev\SlimRbac\Component\PermissionNameExtractor\PermissionNameExtractor;
use Potievdev\SlimRbac\Component\UserIdExtractor\UserIdExtractor;
use Potievdev\SlimRbac\Exception\InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Checking Access Middleware
 * Class RbacMiddleware
 * @package Potievdev\SlimRbac\Component
 */
class RbacMiddleware
{
    public const PERMISSION_DENIED_CODE = 403;
    public const PERMISSION_DENIED_MESSAGE = 'Permission denied';

    /** @var RbacAccessChecker */
    private $accessChecker;

    /** @var UserIdExtractor */
    private $userIdExtractor;

    /** @var PermissionNameExtractor */
    private $permissionNameExtractor;

    /**
     * @param RbacAccessChecker $accessChecker
     * @param UserIdExtractor $userIdExtractor
     */
    public function __construct(
        RbacAccessChecker $accessChecker,
        UserIdExtractor $userIdExtractor,
        PermissionNameExtractor $permissionNameExtractor
    ) {
        $this->accessChecker = $accessChecker;
        $this->userIdExtractor = $userIdExtractor;
        $this->permissionNameExtractor = $permissionNameExtractor;
    }

    /**
     * Check access.
     *
     * @param  ServerRequestInterface $request PSR7 request
     * @param  ResponseInterface $response PSR7 response
     * @param  callable $next Next middleware
     *
     * @return ResponseInterface
     * @throws QueryException
     * @throws InvalidArgumentException
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        $userId = $this->userIdExtractor->getUserId($request);
        $permissionName = $this->permissionNameExtractor->getPermissionName($request);

        if ($this->accessChecker->hasAccess($userId, $permissionName)) {
            return $next($request, $response);
        }

        return $response->withStatus(self::PERMISSION_DENIED_CODE, self::PERMISSION_DENIED_MESSAGE);
    }
}
