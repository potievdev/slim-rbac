<?php

namespace Potievdev\SlimRbac\Structure;

use Doctrine\ORM\EntityManager;

/**
 * Authorization manager options structure.
 * The instance of this class accepted as argument for constructor of RbacManager
 * Class AuthOptions
 * @package Potievdev\Structure
 */
class AuthOptions
{
    /** Default user id field name */
    const DEFAULT_USER_ID_FIELD_NAME = 'userId';

    const ATTRIBUTE_STORAGE_TYPE = 1;
    const HEADER_STORAGE_TYPE = 2;
    const COOKIE_STORAGE_TYPE = 3;

    /** @var  EntityManager */
    private $entityManager;

    /** @var string $userIdFieldName field name which saves user identifier in requests */
    private $userIdFieldName = self::DEFAULT_USER_ID_FIELD_NAME;

    /** @var int $userIdStorageType Type of storage where will be saved user id field */
    private $userIdStorageType = self::ATTRIBUTE_STORAGE_TYPE;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    public function getUserIdFieldName(): string
    {
        return $this->userIdFieldName;
    }

    public function setUserIdFieldName(string $userIdFieldName): void
    {
        $this->userIdFieldName = $userIdFieldName;
    }

    public function getUserIdStorageType(): int
    {
        return $this->userIdStorageType;
    }

    public function setUserIdStorageType(int $userIdStorageType): void
    {
        $this->userIdStorageType = $userIdStorageType;
    }

}
