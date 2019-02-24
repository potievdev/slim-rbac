<?php

namespace Potievdev\SlimRbac\Exception;

/**
 * This throws when cyclic line detected in roles hierarchy
 * Class CyclicException
 * @package Potievdev\SlimRbac\Exception
 */
class CyclicException extends \Exception
{
    protected $message = 'Cyclic role three detected';
}
