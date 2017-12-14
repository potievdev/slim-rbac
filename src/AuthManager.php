<?php

namespace Potievdev\SlimRbac;

use Doctrine\DBAL\Driver\PDOConnection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Potievdev\Exception\PermissionNotFoundException;
use Potievdev\SlimRbac\Models\Entity\Permission;
use Potievdev\SlimRbac\Models\Entity\Role;
use Potievdev\SlimRbac\Models\Entity\RoleHierarchy;
use Potievdev\SlimRbac\Models\Entity\RolePermission;
use Potievdev\SlimRbac\Models\RepositoryRegistry;
use Potievdev\Structure\AuthManagerOptions;

/**
 * Class AuthManager
 * @package Potievdev\SlimRbac\Component
 */
class AuthManager
{
    /** @var  PDOConnection $pdo */
    private $pdo;

    /** @var  EntityManager $entityManager */
    private $entityManager;

    /** @var  RepositoryRegistry $repositoryRegistry */
    private $repositoryRegistry;

    /**
     * AuthManager constructor.
     * @param AuthManagerOptions $options
     */
    public function __construct(AuthManagerOptions $options)
    {
        $paths = [ __DIR__ . "/models/entity" ];
        $isDevMode = $options->getIsDevMode();

        // the connection configuration
        $dbParams = [
            'driver'   => $options->getDatabaseAdapter(),
            'user'     => $options->getDatabaseUsername(),
            'password' => $options->getDatabasePassword(),
            'dbname'   => $options->getDatabaseName(),
            'port'     => $options->getDatabasePort(),
            'charset'  => $options->getDatabaseCharset()
        ];

        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);

        $this->entityManager = EntityManager::create($dbParams, $config);

        $this->pdo = $this->entityManager->getConnection();

        $this->repositoryRegistry = new RepositoryRegistry($this->entityManager);
    }

    /**
     * @param Permission $permission
     */
    public function addPermission(Permission $permission)
    {
        $this->entityManager->persist($permission);
        $this->entityManager->flush();
    }

    /**
     * @param Permission $role
     */
    public function addRole(Permission $role)
    {
        $this->entityManager->persist($role);
        $this->entityManager->flush();
    }

    /**
     * @param Role $role
     * @param Permission $permission
     */
    public function addPermissionToRole(Role $role, Permission $permission)
    {
        $rolePermission = new RolePermission();

        $rolePermission->setPermission($permission);
        $rolePermission->setRole($role);

        $this->entityManager->persist($rolePermission);
        $this->entityManager->flush();
    }

    /**
     * @param Role $parentRole
     * @param Role $childRole
     */
    public function addChildRoleToRole(Role $parentRole, Role $childRole)
    {
        $roleHierarchy = new RoleHierarchy();

        $roleHierarchy->setParentRole($parentRole);
        $roleHierarchy->setChildRole($childRole);

        $this->entityManager->persist($roleHierarchy);
        $this->entityManager->flush();
    }

    /**
     * @param integer $userId
     * @param string $permissionName
     * @param array $params
     * @return bool
     * @throws PermissionNotFoundException
     */
    public function can($userId, $permissionName, $params)
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
}