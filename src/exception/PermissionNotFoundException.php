<?php

namespace Potievdev\Exception;

/**
 * Class PermissionNotFoundException
 * @package Potievdev\Exception
 */
class PermissionNotFoundException extends \Exception
{
    protected $message = 'Permission not found.';
}