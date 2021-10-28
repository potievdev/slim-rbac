<?php

namespace Potievdev\SlimRbac\Models\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\QueryException;
use Potievdev\SlimRbac\Helper\ArrayHelper;

/**
 * UserRoleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RoleHierarchyRepository extends EntityRepository
{
    /**
     * Finding child identifier in roles three where $parentRoleId is in the top of three
     * @throws QueryException
     */
    public function hasChildRoleId(int $parentRoleId, int $findingChildId): bool
    {
        $childIds = $this->getChildIds([$parentRoleId]);

        if (count($childIds) > 0) {

            if (in_array($findingChildId, $childIds)) {
                return true;
            }

            foreach ($childIds as $childId) {

                if ($this->hasChildRoleId($childId, $findingChildId) == true) {
                    return true;
                }

            }
        }

        return false;
    }

    /**
     * @param integer[] $rootRoleIds
     * @return integer[]
     * @throws QueryException
     */
    public function getAllRoleIdsHierarchy(array $rootRoleIds): array
    {
        $childRoleIds = $this->getAllChildRoleIds($rootRoleIds);

        return ArrayHelper::merge($rootRoleIds, $childRoleIds);
    }

    /**
     * Returns all hierarchically child role ids for given parent role ids.
     *
     * @param integer[] $parentIds
     * @return integer[]
     * @throws QueryException
     */
    private function getAllChildRoleIds(array $parentIds): array
    {
        $allChildIds = [];

        while (count($parentIds) > 0) {
            $parentIds = $this->getChildIds($parentIds);
            $allChildIds = ArrayHelper::merge($allChildIds, $parentIds);
        };

        return $allChildIds;
    }

    /**
     * Returns array of child role ids for given parent role ids.
     *
     * @param integer[] $parentIds
     * @return integer[]
     * @throws QueryException
     */
    private function getChildIds(array $parentIds): array
    {
        $qb = $this->createQueryBuilder('roleHierarchy');

        $qb->select('roleHierarchy.childRoleId')
            ->where($qb->expr()->in( 'roleHierarchy.parentRoleId', $parentIds))
            ->indexBy('roleHierarchy', 'roleHierarchy.childRoleId');

        $childRoleIds =  $qb->getQuery()->getArrayResult();

        return array_keys($childRoleIds);
    }
}
