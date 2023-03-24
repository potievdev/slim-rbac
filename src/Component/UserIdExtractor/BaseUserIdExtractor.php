<?php

namespace Potievdev\SlimRbac\Component\UserIdExtractor;

class BaseUserIdExtractor
{
    public const DEFAULT_USER_ID_FIELD_NAME = 'userId';

    protected $userIdFieldName;

    public function __construct(string $userIdFieldName = self::DEFAULT_USER_ID_FIELD_NAME) {
        $this->userIdFieldName = $userIdFieldName;
    }

}