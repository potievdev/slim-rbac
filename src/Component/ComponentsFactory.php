<?php

namespace Potievdev\SlimRbac\Component;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Potievdev\SlimRbac\Component\Config\RbacConfig;
use Potievdev\SlimRbac\Component\UserIdExtractor\AttributeUserIdExtractor;
use Potievdev\SlimRbac\Component\UserIdExtractor\CookieUserIdExtractor;
use Potievdev\SlimRbac\Component\UserIdExtractor\HeaderUserIdExtractor;
use Potievdev\SlimRbac\Component\UserIdExtractor\UserIdExtractor;

class ComponentsFactory
{
    public static function createEntityManager(RbacConfig $rbacConfig): EntityManager
    {
        $dbParams = [
            'driver'   => $rbacConfig->getDatabaseDriver(),
            'host'     => $rbacConfig->getDatabaseHost(),
            'user'     => $rbacConfig->getDatabaseUser(),
            'password' => $rbacConfig->getDatabasePassword(),
            'dbname'   => $rbacConfig->getDatabaseName(),
            'port'     => $rbacConfig->getDatabasePort(),
        ];

        $config = Setup::createAnnotationMetadataConfiguration([], false, null, null, false);

        return EntityManager::create($dbParams, $config);
    }

    public static function createUserIdExtractor(RbacConfig $rbacConfig): UserIdExtractor
    {
        $userIdFieldName = $rbacConfig->getUserIdFieldName();
        $storageType = $rbacConfig->getUserIdResourceType();

        /** @var integer $userId */
        switch ($storageType) {

            case RbacConfig::HEADER_RESOURCE_TYPE:
                return new HeaderUserIdExtractor($userIdFieldName);

            case RbacConfig::COOKIE_RESOURCE_TYPE:
                return new CookieUserIdExtractor($userIdFieldName);

            case RbacConfig::ATTRIBUTE_RESOURCE_TYPE:
            default:
                return new AttributeUserIdExtractor($userIdFieldName);
        }
    }

}