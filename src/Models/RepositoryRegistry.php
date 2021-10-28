<?php

namespace Potievdev\SlimRbac\Models;

use Doctrine\ORM\EntityManager;
use Potievdev\SlimRbac\Models\Repository\PermissionRepository;
use Potievdev\SlimRbac\Models\Repository\RoleHierarchyRepository;
use Potievdev\SlimRbac\Models\Repository\RolePermissionRepository;
use Potievdev\SlimRbac\Models\Repository\RoleRepository;
use Potievdev\SlimRbac\Models\Repository\UserRoleRepository;

class RepositoryRegistry
{
    /** @var  EntityManager $entityManager */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getPermissionRepository(): PermissionRepository
    {
        return $this->entityManager->getRepository('\\Potievdev\\SlimRbac\\Models\\Entity\\Permission');
    }

    public function getRoleRepository(): RoleRepository
    {
        return $this->entityManager->getRepository('\\Potievdev\\SlimRbac\\Models\\Entity\\Role');
    }

    public function getUserRoleRepository(): UserRoleRepository
    {
        return $this->entityManager->getRepository('\\Potievdev\\SlimRbac\\Models\\Entity\\UserRole');
    }

    public function getRolePermissionRepository(): RolePermissionRepository
    {
        return $this->entityManager->getRepository('\\Potievdev\\SlimRbac\\Models\\Entity\\RolePermission');
    }

    public function getRoleHierarchyRepository(): RoleHierarchyRepository
    {
        return $this->entityManager->getRepository('\\Potievdev\\SlimRbac\\Models\\Entity\\RoleHierarchy');
    }

}
