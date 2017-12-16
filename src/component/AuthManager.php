<?php

namespace Potievdev\SlimRbac\Component;

use Doctrine\DBAL\Driver\PDOConnection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Potievdev\Exception\PermissionNotFoundException;
use Potievdev\SlimRbac\Models\Entity\Permission;
use Potievdev\SlimRbac\Models\Entity\Role;
use Potievdev\SlimRbac\Models\Entity\RoleHierarchy;
use Potievdev\SlimRbac\Models\Entity\RolePermission;
use Potievdev\SlimRbac\Models\Entity\UserRole;
use Potievdev\SlimRbac\Models\RepositoryRegistry;
use Potievdev\SlimRbac\Structure\AuthManagerOptions;

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
        $em = $options->getEntityManager();

        if (!isset($em)) {

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
        } else {

            $this->entityManager = $em;
        }

        $this->pdo = $this->entityManager->getConnection()->getWrappedConnection();

        $this->repositoryRegistry = new RepositoryRegistry($this->entityManager);
    }

    /**
     * Truncates all tables
     */
    public function removeAll()
    {
        $this->pdo->query('SET FOREIGN_KEY_CHECKS=0')->execute();
        $this->pdo->prepare('TRUNCATE role_permission')->execute();
        $this->pdo->prepare('TRUNCATE role_hierarchy')->execute();
        $this->pdo->prepare('TRUNCATE role')->execute();
        $this->pdo->prepare('TRUNCATE permission')->execute();
        $this->pdo->prepare('TRUNCATE user_role')->execute();
        $this->pdo->query('SET FOREIGN_KEY_CHECKS=0')->execute();
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
     * @param Role $role
     */
    public function addRole(Role $role)
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
    public function can($userId, $permissionName, $params = [])
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
     * Assign role to user
     * @param integer $userId
     * @param Role $role
     */
    public function assignRoleToUser($userId, Role $role)
    {
        $userRole = new UserRole();

        $userRole->setUserId($userId);
        $userRole->setRole($role);

        $this->entityManager->persist($userRole);
        $this->entityManager->flush();
    }
}