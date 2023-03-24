<?php

namespace Potievdev\SlimRbac\Component;

use Doctrine\ORM\Query\QueryException;
use Potievdev\SlimRbac\Models\RepositoryRegistry;

class RbacAccessChecker
{
    /** @var  RepositoryRegistry $repositoryRegistry */
    private $repositoryRegistry;

    /**
     * @param RepositoryRegistry $repositoryRegistry
     */
    public function __construct(RepositoryRegistry $repositoryRegistry)
    {
        $this->repositoryRegistry = $repositoryRegistry;
    }

    /**
     * Checks access status.
     *
     * @throws QueryException
     */
    public function hasAccess(string $userId, string $permissionName): bool
    {
        /** @var integer $permissionId */
        $permissionId = $this->repositoryRegistry
            ->getPermissionRepository()
            ->getPermissionIdByName($permissionName);

        if ($permissionId === null) {
            return false;
        }

        /** @var integer[] $rootRoleIds */
        $rootRoleIds = $this->repositoryRegistry
            ->getUserRoleRepository()
            ->getUserRoleIds($userId);

        if (count($rootRoleIds) == 0) {
            return false;
        }

        $allRoleIds = $this->repositoryRegistry
            ->getRoleHierarchyRepository()
            ->getAllRoleIdsHierarchy($rootRoleIds);

        return $this->repositoryRegistry
            ->getRolePermissionRepository()
            ->isPermissionAssigned($permissionId, $allRoleIds);
    }
}