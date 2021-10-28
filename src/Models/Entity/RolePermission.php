<?php

namespace Potievdev\SlimRbac\Models\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * RolePermission
 *
 * @ORM\Table(
 *     name="role_permission",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="idx_role_permission_unique", columns={"role_id", "permission_id"})},
 *     indexes={
 *          @ORM\Index(name="fk_role_permission_permission", columns={"permission_id"}),
 *          @ORM\Index(name="fk_role_permission_role", columns={"role_id"})
 *     }
 * )
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
     * @var DateTime
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
     * @var Permission
     *
     * @ORM\ManyToOne(targetEntity="Potievdev\SlimRbac\Models\Entity\Permission")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="permission_id", referencedColumnName="id")
     * })
     */
    private $permission;

    /**
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="Potievdev\SlimRbac\Models\Entity\Role")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     * })
     */
    private $role;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getRoleId(): int
    {
        return $this->roleId;
    }

    public function setRoleId(int $roleId)
    {
        $this->roleId = $roleId;
    }

    public function getPermissionId(): int
    {
        return $this->permissionId;
    }

    public function setPermissionId(int $permissionId)
    {
        $this->permissionId = $permissionId;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getPermission(): Permission
    {
        return $this->permission;
    }

    public function setPermission(Permission $permission)
    {
        $this->permission = $permission;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function setRole(Role $role)
    {
        $this->role = $role;
    }

    /** @ORM\PrePersist */
    public function prePersist()
    {
        $this->createdAt = new DateTime();
    }
}
