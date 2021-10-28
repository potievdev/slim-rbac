<?php

namespace Potievdev\SlimRbac\Exception;

use Exception;

/**
 * Access denied exception
 * Class ForbiddenException
 * @package Potievdev\SlimRbac\Exception
 */
class ForbiddenException extends Exception
{
    protected $message = 'Access denied';
}
