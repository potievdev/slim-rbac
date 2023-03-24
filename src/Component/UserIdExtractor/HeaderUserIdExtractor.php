<?php

namespace Potievdev\SlimRbac\Component\UserIdExtractor;

use Psr\Http\Message\ServerRequestInterface;

class HeaderUserIdExtractor extends BaseUserIdExtractor implements UserIdExtractor
{
    public function getUserId(ServerRequestInterface $request): string
    {
        return $request->getHeaderLine($this->userIdFieldName);
    }
}