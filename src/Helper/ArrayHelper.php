<?php

namespace Potievdev\SlimRbac\Helper;

/**
 * Class ArrayHelper
 * @package Potievdev\SlimRbac\Helper
 */
class ArrayHelper
{
    public static function merge(array $array1, array $array2): array
    {
        return array_merge($array1, $array2);
    }
}
