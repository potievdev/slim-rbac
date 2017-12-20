<?php

namespace Potievdev\SlimRbac\Component;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Potievdev\SlimRbac\Exception\InvalidArgumentException;
use Potievdev\SlimRbac\Helper\ValidatorHelper;
use Potievdev\SlimRbac\Models\RepositoryRegistry;
use Potievdev\SlimRbac\Structure\AuthOptions;

class BaseComponent
{
    /** @var  EntityManager $entityManager */
    protected $entityManager;

    /** @var  RepositoryRegistry $repositoryRegistry */
    protected $repositoryRegistry;

    /**
     * AuthManager constructor.
     * @param AuthOptions $options
     */
    public function __construct(AuthOptions $options)
    {
        $em = $options->getEntityManager();

        if ( ! isset($em)) {

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

        $this->repositoryRegistry = new RepositoryRegistry($this->entityManager);
    }

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

            if (count($rootRoleIds) > 0) {

                /** @var integer[] $allRoleIds */
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