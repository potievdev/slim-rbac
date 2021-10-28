<?php

namespace Potievdev\SlimRbac\Models\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * RoleHierarchy
 *
 * @ORM\Table(
 *     name="role_hierarchy",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="idx_role_hierarchy_unique", columns={"parent_role_id", "child_role_id"})},
 *     indexes={
 *          @ORM\Index(name="fk_role_hierarchy_child", columns={"child_role_id"}),
 *          @ORM\Index(name="fk_role_hierarchy_parent", columns={"parent_role_id"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="Potievdev\SlimRbac\Models\Repository\RoleHierarchyRepository")
 * @ORM\HasLifecycleCallbacks
 */
class RoleHierarchy
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="parent_role_id", type="integer", nullable=false)
     */
    private $parentRoleId;

    /**
     * @var integer
     *
     * @ORM\Column(name="child_role_id", type="integer", nullable=false)
     */
    private $childRoleId;

    /**
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="Potievdev\SlimRbac\Models\Entity\Role")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="child_role_id", referencedColumnName="id")
     * })
     */
    private $childRole;

    /**
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="Potievdev\SlimRbac\Models\Entity\Role")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_role_id", referencedColumnName="id")
     * })
     */
    private $parentRole;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getChildRole(): Role
    {
        return $this->childRole;
    }

    public function setChildRole(Role $childRole)
    {
        $this->childRole = $childRole;
    }

    public function getParentRole(): Role
    {
        return $this->parentRole;
    }

    public function setParentRole(Role $parentRole)
    {
        $this->parentRole = $parentRole;
    }

    /** @ORM\PrePersist */
    public function prePersist()
    {
        $this->createdAt = new DateTime();
    }
}
