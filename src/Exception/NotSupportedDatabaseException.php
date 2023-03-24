<?php

namespace Potievdev\SlimRbac\Exception;

class NotSupportedDatabaseException extends BaseException
{
    public static function notSupportedPlatform(string $platformName): self
    {
        $e = new self("Not supported database platform.");
        $e->additionalParams = ['platformName' => $platformName];

        return $e;
    }
}