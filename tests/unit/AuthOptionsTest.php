<?php

namespace Tests\Unit;

use Doctrine\ORM\EntityManager;
use Potievdev\SlimRbac\Structure\AuthOptions;

/**
 * Class AuthOptionsTest
 * @package Tests\Unit
 */
class AuthOptionsTest extends BaseTestCase
{
    /**
     * Testing AuthOptionsComponent
     */
    public function testCheckSetAndGetMethods()
    {
        $authOptions = $this->authOptions;

        $this->assertInstanceOf(EntityManager::class, $authOptions->getEntityManager());
        $this->assertEquals(AuthOptions::DEFAULT_USER_ID_FIELD_NAME, $authOptions->getUserIdFieldName());
        $this->assertEquals(AuthOptions::ATTRIBUTE_STORAGE_TYPE, $authOptions->getUserIdStorageType());

        $authOptions->setUserIdFieldName('userIdentifier');
        $this->assertEquals('userIdentifier', $authOptions->getUserIdFieldName());

        $authOptions->setUserIdStorageType(AuthOptions::COOKIE_STORAGE_TYPE);
        $this->assertEquals(AuthOptions::COOKIE_STORAGE_TYPE, $authOptions->getUserIdStorageType());
    }
}
