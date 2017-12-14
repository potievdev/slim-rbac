<?php

namespace Potievdev\SlimRbac\Models;

use Doctrine\ORM\EntityManager;

class RepositoryRegistry
{
    /** @var  EntityManager $entityManager */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return \Potievdev\SlimRbac\Models\Repository\PermissionRepository
     */
    public function getPermissionRepository()
    {
        return $this->entityManager->getRepository('\\Potievdev\\SlimRbac\\Models\\Entity\\Permission');
    }

    /**
     * @return \Potievdev\SlimRbac\Models\Repository\RoleRepository
     */
    public function getRoleRepository()
    {
        return $this->entityManager->getRepository('\\Potievdev\\SlimRbac\\Models\\Entity\\Role');
    }

    /**
     * @return \Potievdev\SlimRbac\Models\Repository\UserRoleRepository
     */
    public function getUserRoleRepository()
    {
        return $this->entityManager->getRepository('\\Potievdev\\SlimRbac\\Models\\Entity\\UserRole');
    }

    /**
     * @return \Potievdev\SlimRbac\Models\Repository\RolePermissionRepository
     */
    public function getRolePermissionRepository()
    {
        return $this->entityManager->getRepository('\\Potievdev\\SlimRbac\\Models\\Entity\\RolePermission');
    }

    /**
     * @return \Potievdev\SlimRbac\Models\Repository\RoleHierarchyRepository
     */
    public function getRoleHierarchyRepository()
    {
        return $this->entityManager->getRepository('\\Potievdev\\SlimRbac\\Models\\Entity\\RoleHierarchy');
    }

}
