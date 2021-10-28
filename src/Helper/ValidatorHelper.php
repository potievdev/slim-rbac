<?php

namespace Potievdev\SlimRbac\Helper;

/**
 * Class for validating data
 * Class ValidatorHelper
 * @package Potievdev\SlimRbac\Helper
 */
class ValidatorHelper
{
    /**
     * Checks number for integer type
     * @param mixed $number
     * @return bool
     */
    public static function isInteger($number): bool
    {
        return is_integer($number);
    }

}
