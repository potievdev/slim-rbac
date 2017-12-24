<?php

namespace Tests\Unit;

use Potievdev\SlimRbac\Structure\AuthOptions;

/**
 * Class AuthOptionsTest
 * @package Tests\Unit
 */
class AuthOptionsTest extends \PHPUnit_Framework_TestCase
{
    public function testAuthOptions()
    {
        $authOptions = new AuthOptions();

        $this->assertEquals(AuthOptions::DEFAULT_VARIABLE_NAME, $authOptions->getVariableName());
        $this->assertEquals(AuthOptions::ATTRIBUTE_STORAGE_TYPE, $authOptions->getVariableStorageType());

        $authOptions->setVariableName('var1');
        $this->assertEquals('var1', $authOptions->getVariableName());

        $authOptions->setVariableStorageType(AuthOptions::COOKIE_STORAGE_TYPE);
        $this->assertEquals(AuthOptions::COOKIE_STORAGE_TYPE, $authOptions->getVariableStorageType());
    }
}