<?php

namespace Potievdev\SlimRbac\Exception;

/**
 * Throws when pass incorrect type argument to function
 * Class InvalidArgumentException
 * @package Potievdev\SlimRbac\Exception
 */
class InvalidArgumentException extends \Exception
{
    protected $message = 'The invalid argument passed';
}