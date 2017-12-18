<?php

namespace Potievdev\SlimRbac\Models\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RolePermission
 *
 * @ORM\Table(name="role_permission", uniqueConstraints={@ORM\UniqueConstraint(name="idx_role_permission_unique", columns={"role_id", "permission_id"})}, indexes={@ORM\Index(name="fk_role_permission_permission", columns={"permission_id"}), @ORM\Index(name="IDX_6F7DF886D60322AC", columns={"role_id"})})
 * @ORM\Entity(repositoryClass="Potievdev\SlimRbac\Models\Repository\RolePermissionRepository")
 * @ORM\HasLifecycleCallbacks
 */
class RolePermission
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
     * @ORM\Column(name="role_id", type="integer", nullable=false)
     */
    private $roleId;

    /**
     * @var integer
     *
     * @ORM\Column(name="permission_id", type="integer", nullable=false)
     */
    private $permissionId;

    /**
     * @var \Potievdev\SlimRbac\Models\Entity\Permission
     *
     * @ORM\ManyToOne(targetEntity="Potievdev\SlimRbac\Models\Entity\Permission")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="permission_id", referencedColumnName="id")
     * })
     */
    private $permission;

    /**
     * @var \Potievdev\SlimRbac\Models\Entity\Role
     *
     * @ORM\ManyToOne(targetEntity="Potievdev\SlimRbac\Models\Entity\Role")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     * })
     */
    private $role;

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
     * @return int
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * @param int $roleId
     */
    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;
    }

    /**
     * @return int
     */
    public function getPermissionId()
    {
        return $this->permissionId;
    }

    /**
     * @param int $permissionId
     */
    public function setPermissionId($permissionId)
    {
        $this->permissionId = $permissionId;
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
     * @return Permission
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * @param Permission $permission
     */
    public function setPermission($permission)
    {
        $this->permission = $permission;
    }

    /**
     * @return Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param Role $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /** @ORM\PrePersist */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }
}