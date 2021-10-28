<?php

namespace Potievdev\SlimRbac\Exception;

use Exception;

/**
 * Permission not found in database exception
 * Class PermissionNotFoundException
 * @package Potievdev\SlimRbac\Exception
 */
class PermissionNotFoundException extends Exception
{
    protected $message = 'Permission not found.';
}