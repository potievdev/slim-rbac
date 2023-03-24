<?php

namespace Potievdev\SlimRbac\Exception;

/**
 * This throws when cyclic line detected in roles hierarchy
 * Class CyclicException
 * @package Potievdev\SlimRbac\Exception
 */
class ConfigNotFoundException extends BaseException
{
    public static function configFileNotFound(array $searchedPaths): self
    {
        $e = new self('Config file not found.');
        $e->additionalParams = ['searchedPaths' => $searchedPaths];

        return $e;
    }
}