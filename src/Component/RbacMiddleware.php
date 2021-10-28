<?php

namespace Potievdev\SlimRbac\Component;

use Doctrine\ORM\Query\QueryException;
use Potievdev\SlimRbac\Exception\InvalidArgumentException;
use Potievdev\SlimRbac\Structure\RbacManagerOptions;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Checking Access Middleware
 * Class RbacMiddleware
 * @package Potievdev\SlimRbac\Component
 */
class RbacMiddleware extends BaseComponent
{
    public const PERMISSION_DENIED_CODE = 403;
    public const PERMISSION_DENIED_MESSAGE = 'Permission denied';

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
        $userId = $this->getCurrentUserId($request);
        $permissionName = $request->getUri()->getPath();

        if ($this->checkAccess($userId, $permissionName)) {
            $response = $next($request, $response);
        } else {
            $response = $response->withStatus(self::PERMISSION_DENIED_CODE, self::PERMISSION_DENIED_MESSAGE);
        }

        return $response;
    }

    private function getCurrentUserId(ServerRequestInterface $request): int
    {
        $userIdFieldName = $this->rbacManagerOptions->getUserIdFieldName();
        $storageType = $this->rbacManagerOptions->getUserIdStorageType();

        /** @var integer $userId */
        switch ($storageType) {

            case RbacManagerOptions::ATTRIBUTE_STORAGE_TYPE:
                $userId = intval($request->getAttribute($userIdFieldName));
                break;

            case RbacManagerOptions::HEADER_STORAGE_TYPE:
                $userId = intval($request->getHeaderLine($userIdFieldName));
                break;

            case RbacManagerOptions::COOKIE_STORAGE_TYPE:
                $params = $request->getCookieParams();
                $userId = intval($params[$userIdFieldName]);
                break;
        }

        return $userId;
    }
}
