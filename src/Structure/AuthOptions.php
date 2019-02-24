<?php

namespace Potievdev\SlimRbac\Structure;

/**
 * Authorization manager options structure.
 * The instance of this class accepted as argument for constructor of AuthManager
 * Class AuthOptions
 * @package Potievdev\Structure
 */
class AuthOptions
{

    /** Default field name */
    const DEFAULT_VARIABLE_NAME = 'userId';

    const ATTRIBUTE_STORAGE_TYPE = 1;
    const HEADER_STORAGE_TYPE = 2;
    const COOKIE_STORAGE_TYPE = 3;

    /** @var  \Doctrine\ORM\EntityManager */
    private $entityManager;

    /** @var string  $variableName variable name which saves user identifier */
    private $variableName = self::DEFAULT_VARIABLE_NAME;

    /** @var int $variableStorageType Type of storage where saves variable value */
    private $variableStorageType = self::ATTRIBUTE_STORAGE_TYPE;

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return string
     */
    public function getVariableName()
    {
        return $this->variableName;
    }

    /**
     * @param string $variableName
     */
    public function setVariableName($variableName)
    {
        $this->variableName = $variableName;
    }

    /**
     * @return int
     */
    public function getVariableStorageType()
    {
        return $this->variableStorageType;
    }

    /**
     * @param int $variableStorageType
     */
    public function setVariableStorageType($variableStorageType)
    {
        $this->variableStorageType = $variableStorageType;
    }
}
