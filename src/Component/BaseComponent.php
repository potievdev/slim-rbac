<?php

namespace Potievdev\SlimRbac\Component;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\QueryException;
use Potievdev\SlimRbac\Exception\InvalidArgumentException;
use Potievdev\SlimRbac\Helper\ValidatorHelper;
use Potievdev\SlimRbac\Models\RepositoryRegistry;
use Potievdev\SlimRbac\Structure\AuthOptions;

/**
 * Class BaseComponent
 * @package Potievdev\SlimRbac\Component
 */
class BaseComponent
{
    /** @var  AuthOptions $authOptions */
    protected $authOptions;

    /** @var  EntityManager $entityManager */
    protected $entityManager;

    /** @var  RepositoryRegistry $repositoryRegistry */
    protected $repositoryRegistry;

    /**
     * RbacManager constructor.
     * @param AuthOptions $authOptions
     */
    public function __construct(AuthOptions $authOptions)
    {
        $this->authOptions = $authOptions;
        $this->entityManager = $authOptions->getEntityManager();
        $this->repositoryRegistry = new RepositoryRegistry($this->entityManager);
    }

    /**
     * Checks access status.
     *
     * @throws InvalidArgumentException
     * @throws QueryException
     */
    public function checkAccess(int $userId, string $permissionName): bool
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

            if (count($rootRoleIds) > 0) {

                $allRoleIds = $this->repositoryRegistry
                    ->getRoleHierarchyRepository()
                    ->getAllRoleIdsHierarchy($rootRoleIds);

                return $this->repositoryRegistry
                    ->getRolePermissionRepository()
                    ->isPermissionAssigned($permissionId, $allRoleIds);
            }
        }

        return false;
    }
}
