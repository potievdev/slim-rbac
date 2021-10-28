<?php

namespace Potievdev\SlimRbac\Models\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Permission
 *
 * @ORM\Table(
 *     name="permission",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="idx_permission_name", columns={"name"})}
 * )
 * @ORM\Entity(repositoryClass="Potievdev\SlimRbac\Models\Repository\PermissionRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Permission
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status = '1';

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    private $description;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function isStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status)
    {
        $this->status = $status;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /** @ORM\PrePersist  */
    public function prePersist()
    {
        $this->createdAt = new DateTime();
    }

    /** @ORM\PreUpdate  */
    public function preUpdate()
    {
        $this->updatedAt = new DateTime();
    }
}
