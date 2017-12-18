<?php

namespace Potievdev\SlimRbac\Exception;

/**
 * This throws when cyclic line detected
 * Class CyclicException
 * @package Potievdev\SlimRbac\Exception
 */
class CyclicException extends \Exception
{
    protected $message = 'Cyclic role three detected';
}