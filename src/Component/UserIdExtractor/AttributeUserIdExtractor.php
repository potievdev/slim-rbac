<?php

namespace Potievdev\SlimRbac\Component\UserIdExtractor;

use Psr\Http\Message\ServerRequestInterface;

class AttributeUserIdExtractor extends BaseUserIdExtractor implements UserIdExtractor
{
    public function getUserId(ServerRequestInterface $request): string
    {
        return $request->getAttribute($this->userIdFieldName);
    }
}