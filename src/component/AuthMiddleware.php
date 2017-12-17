<?php

namespace Potievdev\SlimRbac\Component;

use Potievdev\SlimRbac\Exception\InvalidArgumentException;
use Potievdev\SlimRbac\Helper\ValidatorHelper;
use Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;

/**
 * Checking Access Middleware
 * Class AuthMiddleware
 * @package Potievdev\SlimRbac\Component
 */
class AuthMiddleware extends BaseComponent
{
    /**
     * Checks access status
     * @param integer $userId
     * @param string $permissionName
     * @return bool
     * @throws \Exception
     */
    public function checkAccess($userId, $permissionName)
    {
        if (ValidatorHelper::isInteger($userId) == false) {
            throw new InvalidArgumentException('User identifier must be number.');
        }

        /** @var integer $permissionId */
        $permissionId = $this->repositoryRegistry
            ->getPermissionRepository()
            ->getPermissionIdByName($permissionName);

        if (ValidatorHelper::isInteger($permissionId)) {

            /** @var integer[] $rootRoleIds */
            $rootRoleIds = $this->repositoryRegistry
                ->getUserRoleRepository()
                ->getUserRoleIds($userId);

            // If user has not assigned roles
            if (count($rootRoleIds) == 0)
                return false;

            /** @var integer[] $allRoleIds */
            $allRoleIds = $this->repositoryRegistry
                ->getRoleHierarchyRepository()
                ->getAllRoleIdsHierarchy($rootRoleIds);

            return $this->repositoryRegistry
                ->getRolePermissionRepository()
                ->isPermissionAssigned($permissionId, $allRoleIds);
        }

        return false;
    }

    /**
     * Check access
     * @param  ServerRequestInterface                   $request  PSR7 request
     * @param  ResponseInterface                        $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        /** @var integer $userId */
        $userId = $request->getAttribute('userId');

        /** @var string $permissionName */
        $permissionName = $request->getUri()->getPath();

        /** @var bool $permitted */
        $permitted = $this->checkAccess($userId, $permissionName);

        /** @var ResponseInterface $response */
        $response->getBody()->write("$permissionName $permitted");

        $response = $next($request, $response);

        return $response;
    }

}