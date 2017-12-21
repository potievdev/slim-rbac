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

    /** @var  \Doctrine\ORM\EntityManager */
    private $entityManager;

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

}