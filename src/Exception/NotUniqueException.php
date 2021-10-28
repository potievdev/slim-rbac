<?php

namespace Potievdev\SlimRbac\Exception;

use Exception;

/**
 * Class NotUniqueException
 * @package Potievdev\SlimRbac\Exception
 */
class NotUniqueException extends Exception
{
    protected $message = 'Not unique value';
}
