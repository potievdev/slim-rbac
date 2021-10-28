<?php

namespace Tests\Unit;

use Doctrine\ORM\EntityManager;
use Potievdev\SlimRbac\Structure\RbacManagerOptions;

/**
 * Class RbacManagerOptionsTest
 * @package Tests\Unit
 */
class RbacManagerOptionsTest extends BaseTestCase
{
    /**
     * Testing RbacManagerOptionsComponent
     */
    public function testCheckSetAndGetMethods()
    {
        $rbacManagerOptions = $this->rbacManagerOptions;

        $this->assertInstanceOf(EntityManager::class, $rbacManagerOptions->getEntityManager());
        $this->assertEquals(RbacManagerOptions::DEFAULT_USER_ID_FIELD_NAME, $rbacManagerOptions->getUserIdFieldName());
        $this->assertEquals(RbacManagerOptions::ATTRIBUTE_STORAGE_TYPE, $rbacManagerOptions->getUserIdStorageType());

        $rbacManagerOptions->setUserIdFieldName('userIdentifier');
        $this->assertEquals('userIdentifier', $rbacManagerOptions->getUserIdFieldName());

        $rbacManagerOptions->setUserIdStorageType(RbacManagerOptions::COOKIE_STORAGE_TYPE);
        $this->assertEquals(RbacManagerOptions::COOKIE_STORAGE_TYPE, $rbacManagerOptions->getUserIdStorageType());
    }
}
