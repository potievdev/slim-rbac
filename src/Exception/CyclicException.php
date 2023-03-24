<?php

namespace Potievdev\SlimRbac\Exception;

/**
 * This throws when cyclic line detected in roles hierarchy
 * Class CyclicException
 * @package Potievdev\SlimRbac\Exception
 */
class CyclicException extends BaseException
{
    public const MESSAGE = 'Cyclic role three detected';

    public static function cycleDetected(int $parentId, int $childId): self
    {
        $e = new self(self::MESSAGE);

        $e->additionalParams = ['parentId' => $parentId, 'childId' => $childId];

        return $e;
    }
}
