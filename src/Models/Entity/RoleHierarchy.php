<?php

namespace Potievdev\SlimRbac\Models\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RoleHierarchy
 *
 * @ORM\Table(name="role_hierarchy", uniqueConstraints={@ORM\UniqueConstraint(name="idx_role_hierarchy_unique", columns={"parent_role_id", "child_role_id"})}, indexes={@ORM\Index(name="fk_role_hierarchy_child", columns={"child_role_id"}), @ORM\Index(name="IDX_AB8EFB72A44B56EA", columns={"parent_role_id"})})
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
     * @var \DateTime
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
     * @var \Potievdev\SlimRbac\Models\Entity\Role
     *
     * @ORM\ManyToOne(targetEntity="Potievdev\SlimRbac\Models\Entity\Role")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="child_role_id", referencedColumnName="id")
     * })
     */
    private $childRole;

    /**
     * @var \Potievdev\SlimRbac\Models\Entity\Role
     *
     * @ORM\ManyToOne(targetEntity="Potievdev\SlimRbac\Models\Entity\Role")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_role_id", referencedColumnName="id")
     * })
     */
    private $parentRole;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return Role
     */
    public function getChildRole()
    {
        return $this->childRole;
    }

    /**
     * @param Role $childRole
     */
    public function setChildRole($childRole)
    {
        $this->childRole = $childRole;
    }

    /**
     * @return Role
     */
    public function getParentRole()
    {
        return $this->parentRole;
    }

    /**
     * @param Role $parentRole
     */
    public function setParentRole($parentRole)
    {
        $this->parentRole = $parentRole;
    }

    /** @ORM\PrePersist */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }
}