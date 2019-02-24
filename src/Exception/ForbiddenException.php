<?php

namespace Potievdev\SlimRbac\Exception;

/**
 * Access denied exception
 * Class ForbiddenException
 * @package Potievdev\SlimRbac\Exception
 */
class ForbiddenException extends \Exception
{
    protected $message = 'Access denied';
}
