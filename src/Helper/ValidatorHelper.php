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
     * @param integer $number
     * @return bool
     */
    public static function isInteger($number)
    {
        return is_integer($number);
    }

}