<?php

namespace Potievdev\SlimRbac\Component;

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
     * @param integer $userId
     * @param string $permissionName
     * @return bool
     */
    private function checkAccess($userId, $permissionName)
    {
        /** @var integer $permissionId */
        $permissionId = $this->repositoryRegistry
            ->getPermissionRepository()
            ->getPermissionIdByName($permissionName);

        if (is_integer($permissionId)) {

            /** @var integer[] $rootRoleIds */
            $rootRoleIds = $this->repositoryRegistry
                ->getUserRoleRepository()
                ->getUserRoleIds($userId);

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
        $response->getBody()->write("Checking $permissionName $permitted");

        $response = $next($request, $response);

        return $response;
    }

}