<?php

namespace Potievdev\SlimRbac\Models\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * UserRole
 *
 * @ORM\Table(
 *     name="user_role",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="idx_user_role_unique", columns={"user_id", "role_id"})},
 *     indexes={@ORM\Index(name="fk_user_role_role", columns={"role_id"})}
 *     )
 * @ORM\Entity(repositoryClass="Potievdev\SlimRbac\Models\Repository\UserRoleRepository")
 * @ORM\HasLifecycleCallbacks
 */
class UserRole
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
     * @var integer
     *
     * @ORM\Column(name="user_id", type="string", nullable=false)
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="role_id", type="integer", nullable=false)
     */
    private $roleId;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

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

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId)
    {
        $this->userId = $userId;
    }

    public function getRoleId(): int
    {
        return $this->roleId;
    }

    public function setRoleId(int $roleId)
    {
        $this->roleId = $roleId;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
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
